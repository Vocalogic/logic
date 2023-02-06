<div class="row">
    <div class="col-xs-12 col-lg-6">
        @if(hasIntegration(\App\Enums\Core\IntegrationType::Merchant))
            @include('admin.accounts.billing.method')
        @endif
    </div>

    <div class="col-xs-12 col-lg-6">

        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Invoice Settings</h6>
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
                            @props(['opts' => [0 => 'No', 1 => 'Yes']])
                            <x-form-select name="taxable" icon="minus-square" label="Customer Taxable?" :options="$opts" selected="{{$account->taxable}}">
                                Is this customer taxable? Select No for no sales tax
                            </x-form-select>
                        </div>
                        <div class="col-lg-12 mt-2">
                            <button type="submit" class="btn btn-primary ladda pull-right" data-style="zoom-out">
                                <i class="fa fa-save"></i> Save Invoice Settings
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>


        <div class="card mt-4">
            <div class="card-body">
                <p class="card-title">Service Billing Properties</p>
                <form method="POST" action="/admin/accounts/{{$account->id}}" class="properties">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-floating">
                                <input type="date" class="form-control" name="next_bill"
                                       value="{{$account->next_bill?->format("Y-m-d")}}">
                                <label>Next Bill Date</label>
                                <span class="helper-text">Update the next service invoice date.</span>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 mt-2">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="bills_on" value="{{$account->bills_on}}">
                                <label>Bill Day of Month</label>
                                <span class="helper-text">This account always bills on which day?</span>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 mt-2">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="net_terms"
                                       value="{{$account->net_terms}}">
                                <label>NET Terms</label>
                                <span class="helper-text">Days given to pay bill before late?</span>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 mt-2">
                            <div class="form-floating">
                                {!! Form::select('payment_method', \App\Enums\Core\PaymentMethod::selectable(), $account->payment_method?->value, ['class' => 'form-control']) !!}
                                <label>Payment Method</label>
                                <span class="helper-text">Default Payment Method for Monthly Invoices</span>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 mt-2">
                            <div class="form-floating">
                                {!! Form::select('auto_bill', [0 => 'No', 1 => 'Yes'], $account->auto_bill, ['class' => 'form-control']) !!}
                                <label>Auto-Bill on Due Date?</label>
                                <span class="helper-text">If Yes, Logic will attempt to bill customers payment method automatically on due date.</span>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 mt-3">
                            <button type="submit" class="btn btn-primary ladda" data-style="zoom-out">
                                <i class="fa fa-save"></i> Update Service Billing
                            </button>

                        </div>

                    </div>
                </form>
            </div>
        </div>



    </div>




</div>
