<div class="row">
    <div class="col-lg-6">
        <form method="POST" action="/shop/account/services/{{$item->id}}/term">
            @csrf
            @method('POST')
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-floating">
                        <textarea  class="form-control" style='height: 100px;' name="requested_termination_reason">{!! $item->requested_termination_reason !!}</textarea>
                        <label>Termination Reason</label>
                        <span class="helper-text">Enter the reason for termination below.</span>
                    </div>
                    <input type="submit" class="btn btn-info" value="Submit Cancellation Request">
                </div>
            </div>
        </form>
    </div>

    <div class="col-lg-6">
        @if($item->payoffAmount > 0)
            <h4 class="text-center">Payoff Information</h4>
            <p class="mt-2">
                This item is contracted until <b>{{$item->quote->contract_expires->format("F d, Y")}}</b>.
                According to your agreement, terminating early will require a payoff of
                <b>{{setting('account.term_payoff')}}%</b> of the remaining
                <b>{{now()->diffInMonths($item->quote->contract_expires)}} months</b> contracted.
            </p>
            <p>
                Terminating this service will result in a one-time invoice generated in the amount of:
                <b>${{moneyFormat($item->payOffAmount)}}</b>.
            </p>
        @else
            <h6>Item not Contracted</h6>
            <p>
                This item is currently not under a contract and can be terminated at anytime.
            </p>
        @endif

    </div>


</div>
