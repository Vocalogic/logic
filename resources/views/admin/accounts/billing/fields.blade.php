<div class="row">
    <div class="col-xs-12 col-lg-6">
        @if(hasIntegration(\App\Enums\Core\IntegrationType::Merchant))
            @include('admin.accounts.billing.method')
        @endif
    </div>

    <div class="col-xs-12 col-lg-6">

        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Billing Settings</h6>
                <p>
                    If you would like for invoices and other billing emails to go to a specific email
                    address, you can add it below, otherwise it will use the primary user on the account.
                </p>
                <form method="POST" action="/admin/accounts/{{$account->id}}/billing">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <x-form-input name="billing_email" icon="reply-all"
                                          label="Billing E-mail" value="{{$account->billing_email}}">
                                Enter email address for accounting, or leave blank for admin user to get billing emails.
                            </x-form-input>

                            <x-form-input name="po" icon="folder-o"
                                          label="Purchase Order" value="{{$account->po}}">
                                If your customer requires a purchase order for monthly services, you can enter it here.
                            </x-form-input>


                        </div>
                        <div class="offset-4 col-lg-8 mt-2">
                            <input type="submit" name="save" value="Update Billing Settings"
                                   class="btn w-100 btn-light-primary wait">
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>

</div>
