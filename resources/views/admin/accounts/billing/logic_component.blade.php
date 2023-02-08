<div>
    @if(!$complete)
        <div class="mt-3">
            <div class="alert alert-{{$messageColor}}">{!! $message !!}</div>
            <div class="row">
                <div class="col-lg-12">
                    <p class="card-text">
                        If you wish to change the payment method on file you can update it here.
                    </p>
                </div>
            </div>


            <form name="tokenform" id="tokenform">


                <div class="row mt-4">
                    <div class="col-lg-4">
                        <label>Credit Card Number</label>
                    </div>
                    <div class="col-lg-8">
                        <iframe id="tokenframe" name="tokenframe"
                                src="https://{{env('APP_ENV') == 'local' ? "isv-uat" : "isv"}}.cardconnect.com/itoke/ajax-tokenizer.html?formatinput=true"
                                scrolling="no" width="500" height="50" frameborder="0"></iframe>
                        <input type="hidden" name="mytoken" id="mytoken"/>
                    </div>
                </div>
                @if($token)

                    <div class="row">
                        <div class="col-lg-4">
                            <label>Expiration Date (MMYY)</label>
                        </div>
                        <div class="col-lg-8">
                            <input class="form-control" wire:model="expiration" placeholder="MMYY">
                        </div>
                    </div>
                @endif

                @if($validExpiration)
                    <div class="row mt-3">
                        <div class="col-lg-4">
                            <label>CVV (Security Code)</label>
                        </div>
                        <div class="col-lg-8">
                            <input class="form-control" wire:model="cvv">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-lg-4">
                            <label>Billing Zip Code</label>
                        </div>
                        <div class="col-lg-8">
                            <input class="form-control" wire:model="postal">
                        </div>
                    </div>
                @endif


                @if($canAttempt)
                    <div class="row mt-3 mb-4">
                        <div class="col-lg-12">
                            <a href="#" wire:click="attemptAuthorization" data-style="zoom-out"
                                    class="btn btn-sm ladda {{user()->account_id > 1 ? "bg-primary text-white" : "btn-primary"}}">
                                Pre-Authorize Card
                            </a>
                        </div>
                    </div>
                @endif

            </form>



        </div>

    @else
        <h5 class="text-success">Card Successfully Authorized!</h5>
    @endif

</div>
