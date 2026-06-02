<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret    = config('services.stripe.webhook');

        // Verify webhook signature if secret is configured
        if ($secret) {
            try {
                $event = Webhook::constructEvent($payload, $sigHeader, $secret);
            } catch (SignatureVerificationException $e) {
                return response()->json(['error' => 'Invalid signature'], 400);
            }
        } else {
            // In development without webhook secret, parse the payload directly
            $event = json_decode($payload);
        }

        // Handle checkout.session.completed
        if (($event->type ?? null) === 'checkout.session.completed') {
            $session   = $event->data->object;
            $invoiceId = $session->metadata->invoice_id ?? null;

            if ($invoiceId) {
                $invoice = Invoice::find($invoiceId);

                if ($invoice) {
                    // Mark invoice as paid
                    $invoice->update(['status' => 'paid']);

                    // Create transaction record
                    Transaction::create([
                        'invoice_id' => $invoice->id,
                        'amount'     => $invoice->amount + $invoice->tax,
                        'gateway'    => 'stripe',
                        'reference'  => $session->id,
                        'paid_at'    => now(),
                    ]);
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
