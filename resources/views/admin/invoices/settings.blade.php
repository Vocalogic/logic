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
                <button type="submit" class="btn btn-primary ladda pull-right" data-style="zoom-out">
                    <i class="fa fa-save"></i> Save Settings
                </button>
            </div>
        </div>
    </div>
</form>
