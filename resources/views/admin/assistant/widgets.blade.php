<div class="col-lg-3">
    <div class="card py-2 px-3 me-2 mt-2">
        <small class="text-muted">Session Active</small>
        <div class="fs-5">
            @if($cart->get('last_activity')->diffInSeconds() < 10)
                <span class="badge bg-success"><i class="fa fa-clock-o"></i> User Active</span>
            @elseif($cart->get('last_activity')->diffInSeconds() >= 10 && $cart->get('last_activity')->diffInSeconds() <= 20)
                <span class="badge bg-warning"><i class="fa fa-exclamation-triangle"></i> Lost Connection</span>
            @else
                {{$cart->get('last_activity')->diffForHumans()}}
            @endif
        </div>
    </div>
</div>

<div class="col-lg-3">
    <div class="card py-2 px-3 me-2 mt-2">
        <small class="text-muted">Browser</small>
        <div class="fs-9">{{\Illuminate\Support\Str::limit($cart->get('browser'), 175)}}</div>
    </div>
</div>

<div class="col-lg-3">
    <div class="card py-2 px-3 me-2 mt-2">
        <small class="text-muted">IP Address</small>
        <div class="fs-5">{{$cart->get('ip')}}</div>
    </div>
</div>

<div class="col-lg-3">
    <div class="card py-2 px-3 me-2 mt-2">
        <small class="text-muted">Total in Cart</small>
        <div class="fs-5">${{moneyFormat($cart->get('cart')->get('total'))}}</div>
    </div>
</div>
