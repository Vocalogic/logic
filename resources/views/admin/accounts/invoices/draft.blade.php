<div class="row mb-3">
    @foreach($account->invoices()->where('status', 'draft')->get() as $invoice)        <div class="col-3">
            @include('admin.partials.invoice_card', ['invoice' => $invoice])
        </div>
    @endforeach
</div>

