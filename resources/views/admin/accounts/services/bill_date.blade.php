<ul class="nav nav-tabs tab-card" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#profit" role="tab">Profit ({{$account->analysis->margin}}%)</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#settings" role="tab">Settings</a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane fade" id="settings" role="tabpanel">

        <div class="card">
            <div class="card-body">
                <p class="card-title">Service Billing Properties</p>
                <form method="POST" action="/admin/accounts/{{$account->id}}">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="next_bill"
                                       value="{{$account->next_bill?->format("m/d/y")}}">
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
                            <input type="submit" name="save" value="Save" class="btn btn-primary">
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="tab-pane  active" id="profit" role="tabpanel">
        @include('admin.accounts.services.profit')
    </div>



</div>
