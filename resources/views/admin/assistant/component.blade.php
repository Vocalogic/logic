<div>
    <div wire:poll.2000ms="live"></div>
    @if(isset($cart->get('cart')->get('quote')->id))
        <div class="alert alert-warning">
    This cart is controlled by <a href="/admin/quotes/{{$cart->get('cart')->get('quote')->id}}">Quote #{{$cart->get('cart')->get('quote')->id}}</a>. Updates
    cannot be made here and must be made from within the quote. Customer's cart is locked to the quote.
</div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                @include('admin.assistant.widgets')
            </div>
        </div>
        <div class="col-lg-9 mt-3">
            @include('admin.assistant.commands')
        </div>
        <div class="col-lg-3 mt-4">

            @if($this->cart->get('command'))
                <div class="alert border-primary p-3"><i class="fa fa-spin fa-recycle"></i> Waiting for Client Resync..</div>
            @endif
        </div>

    </div>


    <div class="row mt-5">

        <div class="col-lg-6">
            @include('admin.assistant.products')
        </div>
        <div class="col-lg-6">
            @include('admin.assistant.services')
        </div>
    </div>


</div>
