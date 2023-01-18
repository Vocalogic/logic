<div>
    <div wire:poll.5000ms="loadActivity"></div>
    @if(count($carts))
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="card-title">Active Carts</h6>
                @foreach($carts as $uid => $cart)
                <div class="mb-3">
                    <div class="mb-0 fw-bold">{{$cart->ip}} <a href="/admin/cart/{{$cart->id}}">
                            <span class="badge bg-primary"><i class="fa fa-chevron-right"></i> {{$cart->code}}</span>
                        </a></div>
                    <small class="text-muted">{{\Illuminate\Support\Str::limit($cart->browser, 40)}} /
                        Total ${{moneyFormat($cart->cart->get('total'))}} /
                        <br/>Last Active: {{$cart->last_activity->diffForHumans()}}</small>
                    @if(isset($ipLocators[$cart->id]) && isset($ipLocators[$cart->id]->city))
                        <br/><small>Located: {{$ipLocators[$cart->id]->city}}, {{$ipLocators[$cart->id]->regionName}} - {{$ipLocators[$cart->id]->isp}}</small>
                    @endif
                    @if($cart->cart && $cart->cart->get('quote')->id)
                        <span class="badge bg-{{bm()}}info">customer viewing <a href="/admin/quotes/{{$cart->cart->get('quote')->id}}">Quote #{{$cart->cart->get('quote')->id}}</a></span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>


    @endif
</div>
