<form method="post" action="/admin/invoices/{{$invoice->id}}/settings" class="invoiceSettingForm">
    @method('POST')
    @csrf
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <x-form-input name="po" label="Purchase Order Number" icon="folder-o"
                          value="{{$invoice->po}}">
                If this invoice should be assigned a PO, enter it here.
            </x-form-input>
        </div>

        <div class="row mt-2">
            <div class="col-lg-12">
                <input type="submit" name="submit" value="Create Invoice"
                       class="btn btn-{{bm()}}primary w-100 wait" data-anchor=".newInvoiceForm">
            </div>
        </div>
    </div>
</form>
