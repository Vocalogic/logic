<section class="user-dashboard-section section-b-space" style="padding:0px;">
    <div class="dashboard-right-sidebar">
        <div class="dashboard-home">
            <div class="total-box">
                <div class="row g-sm-4 g-3">
                    @foreach($orders as $order)

                        <div class="col-xxl-6 col-lg-6 col-md-12 col-sm-12">
                            <a href="/shop/account/orders/{{$order->hash}}">
                                <div class="totle-contain">
                                    <img src="/ec/assets/images/svg/pending.svg"
                                         class="img-1 blur-up lazyloaded" alt="">
                                    <img src="/ec/assets/images/svg/pending.svg" class="blur-up lazyloaded"
                                         alt="">
                                    <div class="totle-detail">
                                        <h5>Order {{$order->hash}}</h5>
                                        <h3>
                                            Status: {{$order->status}}
                                        </h3>
                                    </div>
                                </div>
                            </a>
                        </div>

                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
