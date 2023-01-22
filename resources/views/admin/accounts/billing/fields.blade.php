<div class="row">
    <div class="col-xs-12 col-lg-6">
        @if(hasIntegration(\App\Enums\Core\IntegrationType::Merchant))
            @include('admin.accounts.billing.method')
        @endif
    </div>

    <div class="col-xs-12 col-lg-6">

        <div class="card">
            <div class="card-body">
                <p>
                    If you would like for invoices and other billing emails to go to a specific email
                    address, you can
                    add it below, otherwise it will use the primary user on the account.
                </p>
                <form method="POST" action="/admin/accounts/{{$account->id}}/billing">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="billing_email"
                                       value="{{$account->billing_email}}">
                                <label>Billing E-mail</label>
                                <span
                                    class="helper-text">Leave blank to use admin user for accounting.</span>
                            </div>
                        </div>
                        <div class="col-lg-12 mt-2">
                            <input type="submit" name="save" value="Update Billing Email"
                                   class="btn btn-light-primary wait">
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>

</div>
