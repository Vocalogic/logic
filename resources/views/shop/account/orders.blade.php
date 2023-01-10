@if($account->orders()->where('active', true)->count() == 0)
    You have no active orders.
@else

        <div class="total-box">
            <div class="row g-sm-4 g-3">

                @foreach($account->orders()->where('active', true)->get() as $order)

                    <div class="col-xxl-12 col-lg-12 col-md-12 col-sm-12 mb-3">
                        <a href="/shop/account/orders/{{$order->hash}}">
                            <div class="totle-contain">
                                <img src="/ec/assets/images/svg/order.svg"
                                     class="img-1 blur-up lazyloaded" alt="">
                                <img src="/ec/assets/images/svg/order.svg" class="blur-up lazyloaded"
                                     alt="">
                                <div class="totle-detail">
                                    <h5>Order #{{$order->id}}</h5>
                                    <h3>
                                        {{$order->status}}
                                    </h3>
                                </div>
                            </div>
                        </a>
                    </div>

                @endforeach
            </div>


        </div>


@endif
