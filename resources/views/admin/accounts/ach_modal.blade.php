<p>
    <b>NOTE!</b> By updating ACH information, you will not clear out any account credit card information. This will
    be added as an alternate payment method. You must set the default payment type to ACH should you wish to use
    this as the default payment method.
</p>
<p>
    <b>WARNING!</b> No pre-authorization for ACH is available. Payment will be attempted at the time of invoice.
    <code>CHECK YOUR INFORMATION CAREFULLY!</code>
</p>
<form method="POST" action="/admin/accounts/{{$account->id}}/updateACH">
    @csrf
    @method('POST')
    <div class="row">
        <div class="col-lg-6">
            <div class="form-floating">
                <input type="text" class="form-control" name="routing" value="{{$account->merchant_ach_aba}}">
                <label>Routing Number</label>
                <span class="helper-text">Enter the routing number for the checking account.</span>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-floating">
                <input type="text" class="form-control" name="account" value="{{$account->merchant_ach_account}}">
                <label>Account Number</label>
                <span class="helper-text">Enter the account number for the checking account.</span>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <input type="submit" class="btn btn-primary" value="Update ACH Information">
    </div>
</form>
