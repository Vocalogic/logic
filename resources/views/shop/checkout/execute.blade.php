<div class="row g-2 mb-3">

    <div class="col-lg-12">
        <p class="text-content">
            @if(cart()->quote && cart()->quote->id && cart()->quote->term > 0)
                {!! cart()->quote->msaContent !!}
            @endif
        </p>
    </div>

    <div class="col-lg-6">
        <div class="col-md-12 col-12">
            <div class="form-floating">
                <input type="text" class="form-control" name="name" value="" wire:model="signName">
                <label>Signer's Name</label>
                <span class="helper-text">Enter your name as you will sign below.</span>
            </div>
        </div>
        <div class="col-md-12 mt-4">

            <div class="input-field col s12 center">
                <div class='sigPad sigWrapper'>
                    <canvas class='pad' width='380' height='100'
                            style='border: 1px solid #000088'></canvas>
                    <input type='hidden' name='output' class='output' wire:model="signature">
                </div>

            </div>

        </div>
        <div class="col-lg-12 mt-5">
            <span class="helper-text">Please sign your name with your mouse. This signature will be applied
            to your order and executed from IP: {{app('request')->ip()}}.</span>
        </div>

    </div>

    <div class="col-lg-6">
        <div class="category-menu">
            <h3>Before you Execute..</h3>
            @if(auth()->guest())
                <ul>
                    <li>A verification email will be sent on all new accounts to validate your email.</li>
                    <li>All unverified orders will be verified via your contact number before execution.</li>
                    <li>Once executed, you will be directed to your account and able to provide payment. An invoice will
                        also be sent to your email.
                    </li>
                </ul>
            @else
                <ul>
                    <li>This order will be placed for <strong>{{user()->account->name}}</strong></li>
                    <li>Products ordered will be billed immediately and processed without verification unless otherwise
                        stated.
                    </li>
                    <li>Monthly services added will be applied immediately and invoiced.</li>
                </ul>
            @endif
        </div>


    </div>


</div>

@if(!$verified)
    @livewire('verification-component')
@endif

@if($verified)
    <button class="btn btn-block text-white bg-success" wire:click="execute()" wire:loading.attr="disabled"><i
            class="fa fa-arrow-right"></i>
        <span wire:loading.remove>Execute</span>
        <span wire:loading>Please Wait...</span>
    </button>
@endif
