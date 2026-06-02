<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The invoice instance.
     */
    public Invoice $invoice;

    /**
     * The Stripe Checkout payment URL.
     */
    public string $payUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Invoice $invoice, string $payUrl)
    {
        $this->invoice = $invoice->load('customer');
        $this->payUrl  = $payUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Invoice {$this->invoice->invoice_number} from LeadFlow CRM",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.invoice',
        );
    }
}
