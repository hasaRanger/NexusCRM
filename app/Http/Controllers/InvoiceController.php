<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

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

        Invoice::create($validated);

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

        return back()->with('success', 'Status updated.');
    }
}
