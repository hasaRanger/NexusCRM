<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

class InvoiceController extends Controller
{
    public function index(): Response
    {
        $invoices = Invoice::with('customer:id,name')
            ->latest()
            ->paginate(15);

        return Inertia::render('Invoices/Index', [
            'invoices' => $invoices,
        ]);
    }

    public function create(): Response
    {
        $customers = Customer::where('status', 'active')
            ->select(['id', 'name'])
            ->get();

        return Inertia::render('Invoices/Create', [
            'customers' => $customers,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'amount'      => ['required', 'numeric', 'min:0'],
            'tax'         => ['nullable', 'numeric', 'min:0'],
            'due_date'    => ['nullable', 'date'],
            'status'      => ['required', 'in:draft,sent,paid'],
        ]);

        // Auto-generate invoice_number as INV- + zero-padded total count
        $totalCount = Invoice::withTrashed()->count();
        $invoiceNumber = 'INV-' . str_pad($totalCount + 1, 4, '0', STR_PAD_LEFT);

        $validated['invoice_number'] = $invoiceNumber;

        $invoice = Invoice::create($validated);

        $this->syncTransaction($invoice);

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice created successfully.');
    }

    public function edit(Invoice $invoice): Response
    {
        $invoice->load('customer:id,name');

        $customers = Customer::where('status', 'active')
            ->select(['id', 'name'])
            ->get();

        return Inertia::render('Invoices/Edit', [
            'invoice'   => $invoice,
            'customers' => $customers,
        ]);
    }

    public function update(Request $request, Invoice $invoice): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'amount'      => ['required', 'numeric', 'min:0'],
            'tax'         => ['nullable', 'numeric', 'min:0'],
            'due_date'    => ['nullable', 'date'],
            'status'      => ['required', 'in:draft,sent,paid'],
        ]);

        $invoice->update($validated);

        $this->syncTransaction($invoice);

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice updated successfully.');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice deleted.');
    }

    public function changeStatus(Request $request, Invoice $invoice): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:draft,sent,paid'],
        ]);

        $invoice->update($validated);

        $this->syncTransaction($invoice);

        return back()->with('success', 'Status updated.');
    }

    private function syncTransaction(Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            if (!$invoice->transaction) {
                Transaction::create([
                    'invoice_id' => $invoice->id,
                    'amount'     => $invoice->amount + ($invoice->tax ?? 0),
                    'gateway'    => 'manual',
                    'reference'  => 'Manual Payment',
                    'paid_at'    => now(),
                ]);
            }
        } else {
            if ($invoice->transaction) {
                $invoice->transaction->delete();
            }
        }
    }

    public function send(Invoice $invoice): RedirectResponse
    {
        if ($invoice->status !== 'draft') {
            return back()->with('error', 'Only draft invoices can be sent.');
        }

        $invoice->load('customer');

        // Set Stripe API key
        Stripe::setApiKey(config('services.stripe.secret'));

        // Calculate total in cents
        $totalCents = (int) round(($invoice->amount + $invoice->tax) * 100);

        // Create Stripe Checkout Session
        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency'     => 'usd',
                    'product_data' => [
                        'name'        => "Invoice {$invoice->invoice_number}",
                        'description' => "Payment for invoice {$invoice->invoice_number}",
                    ],
                    'unit_amount' => $totalCents,
                ],
                'quantity' => 1,
            ]],
            'mode'        => 'payment',
            'success_url' => route('invoices.paymentSuccess') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('invoices.index') . '?payment=cancelled',
            'metadata'    => [
                'invoice_id' => $invoice->id,
            ],
        ]);

        // Save session ID and mark as sent
        $invoice->update([
            'stripe_session_id' => $session->id,
            'status'            => 'sent',
        ]);

        // Email the customer with pay link
        Mail::to($invoice->customer->email)
            ->send(new InvoiceMail($invoice, $session->url));

        return back()->with('success', 'Invoice sent to ' . $invoice->customer->email . '.');
    }

    public function paymentSuccess(Request $request): RedirectResponse
    {
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return redirect()->route('invoices.index')
                ->with('error', 'Invalid payment session.');
        }

        // Find the invoice by stripe_session_id
        $invoice = Invoice::where('stripe_session_id', $sessionId)->first();

        if (!$invoice) {
            return redirect()->route('invoices.index')
                ->with('error', 'Invoice not found for this payment session.');
        }

        // If already paid (e.g. webhook already processed it), just redirect
        if ($invoice->status === 'paid') {
            return redirect()->route('invoices.index')
                ->with('success', 'Payment already recorded.');
        }

        // Verify the session with Stripe
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $session = StripeSession::retrieve($sessionId);
        } catch (\Exception $e) {
            return redirect()->route('invoices.index')
                ->with('error', 'Could not verify payment with Stripe.');
        }

        if ($session->payment_status === 'paid') {
            // Mark invoice as paid
            $invoice->update(['status' => 'paid']);

            // Create transaction record (if not already created by webhook)
            if (!$invoice->transaction) {
                Transaction::create([
                    'invoice_id' => $invoice->id,
                    'amount'     => $invoice->amount + $invoice->tax,
                    'gateway'    => 'stripe',
                    'reference'  => $session->payment_intent ?? $sessionId,
                    'paid_at'    => now(),
                ]);
            }

            return redirect()->route('invoices.index')
                ->with('success', 'Payment successful! Invoice #' . $invoice->invoice_number . ' marked as paid.');
        }

        return redirect()->route('invoices.index')
            ->with('error', 'Payment not completed.');
    }
}
