<div class="row mb-3 mt-4">
    @foreach($account->invoices()->whereIn('status', [\App\Enums\Core\InvoiceStatus::SENT, \App\Enums\Core\InvoiceStatus::PARTIAL])->get() as $invoice)
        <div class="col-lg-3 col-xs-12 mt-3">
            @include('admin.partials.invoice_card', ['invoice' => $invoice])
        </div>
    @endforeach
</div>

