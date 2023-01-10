<div class="card">
    <div class="card-body">
        <h6 class="card-title">Cart Commands</h6>

        <a class="btn btn-sm btn-primary live" href="/admin/cart/{{$cart->get('id')}}/prepare/url" data-title="Send Customer to Page"><i class="fa fa-arrow-right"></i> Send Customer to Item</a>
        <a class="btn btn-sm btn-primary" href="/admin/cart/{{$cart->get('id')}}/command/reload"><i class="fa fa-recycle"></i> Refresh Customer Page</a>
        <a class="btn btn-sm btn-info" href="/admin/cart/{{$cart->get('id')}}/command/review"><i class="fa fa-cart-plus"></i> Cart Review</a>
        <a class="btn btn-sm btn-success live" data-title="Convert Cart to Quote" href="/admin/cart/{{$cart->get('id')}}/prepare/quote"><i class="fa fa-money"></i> Convert to Quote</a>

        <a class="btn btn-sm btn-info" href="/admin/cart/{{$cart->get('id')}}/command/request"><i class="fa fa-exclamation"></i> Send Notice</a>


    </div>
</div>
