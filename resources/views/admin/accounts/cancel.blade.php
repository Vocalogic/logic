<p class="card-text">
    You can cancel an account here. This will make the account inactive and will suspend all billing services. Enter
    a note below as to why this account was cancelled.
</p>
<form method="POST" action="/admin/accounts/{{$account->id}}/cancel">
    @csrf
    @method('POST')
    <div class="row">
        <div class="col-12">
            <div class="form-floating">
                <textarea name="reason" class="form-control"></textarea>
                <label>Cancellation Reason</label>
                <span class="helper-text">Enter a reason why this account has been cancelled</span>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-lg-12 ">
            <input type="submit" name="save" value="Cancel Account" class="btn btn-primary wait" data-anchor=".modal">
        </div>
    </div>
</form>
