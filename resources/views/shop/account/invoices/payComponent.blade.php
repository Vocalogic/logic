<div>
    @if($errorMessage)
        <div class="alert alert-danger">{!! $errorMessage !!}</div>
    @endif
    <div class="button-group cart-button">
        <ul>
            <li>
                @if($invoice->balance > 0)
                    <button wire:click="authorize" wire:loading.attr="disabled"
                            class="btn btn-block btn-animation proceed-btn fw-bold">
                        <span wire:loading.remove>Pay ${{moneyFormat($invoice->balance)}}</span>
                        <span wire:loading>Authorizing Payment...</span>
                    </button>
                @else
                    <div class="alert alert-success">
                        Invoice #{{$invoice->id}} has been paid. <a href="/shop/account">Return to Account Overview.</a>
                    </div>
                @endif
            </li>
        </ul>
    </div>
</div>
