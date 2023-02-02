<div>
    <div class="mt-3">
        <div class="alert alert-{{$messageColor}}">{!! $message !!}</div>
        <div class="row">
            <div class="col-lg-12">
                <p class="card-text">
                    If you wish to change the payment method on file you can update it here. Please note
                    that you must verify your expiration date after pre-authorizing your card.
                </p>
            </div>
        </div>

        @if($awaitingExpiration)
            <div class="row mt-3">
                <div class="col-lg-12">
                    <h4><i class="fa fa-exclamation-circle text-warning"></i> Verify Expiration Date</h4>
                    <div class="row">
                        <div class="col-lg-6 mt-2">
                            <div class="form-floating">
                                <input type="text" wire:model="expiration" class="form-control">
                                <label>Verify Expiration Date (MMYY)</label>
                            </div>
                            <button wire:click="saveExpiration" class="btn bg-primary text-white mt-3 mb-3"><i
                                    class="fa fa-credit-card"></i> Save Expiration Date
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        @endif
    </div>
</div>
