@component('mail::message')
# Hello {{ $invoice->customer->name }},

You have received a new invoice from **{{ config('app.name') }}**.

---

**Invoice Number:** {{ $invoice->invoice_number }}

**Amount:** ${{ number_format($invoice->amount, 2) }}

**Tax:** ${{ number_format($invoice->tax, 2) }}

**Total Due:** ${{ number_format($invoice->amount + $invoice->tax, 2) }}

@if($invoice->due_date)
**Due Date:** {{ $invoice->due_date->format('M d, Y') }}
@endif

---

Please click the button below to pay this invoice securely via Stripe:

@component('mail::button', ['url' => $payUrl, 'color' => 'primary'])
Pay Now
@endcomponent

If you have any questions about this invoice, please don't hesitate to contact us.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
