
@if($lead->quotes()->where('presentable', true)->count())
    <div class="total-box">
        <div class="row g-sm-4 g-3">
            @foreach($lead->quotes()->where('presentable', true)->where('archived', false)->get() as $quote)

                <div class="col-xxl-4 col-lg-6 col-md-4 col-sm-6">
                    <a href="/shop/presales/{{$lead->hash}}/{{$quote->hash}}">
                        <div class="totle-contain">
                            <img src="/ec/assets/images/svg/order.svg"
                                 class="img-1 blur-up lazyloaded" alt="">
                            <img src="/ec/assets/images/svg/order.svg" class="blur-up lazyloaded"
                                 alt="">
                            <div class="totle-detail">
                                <h5>View Quote #{{$quote->id}}</h5>
                                <h3>
                                    Expires {{$quote->expires_on->format("M d")}}
                                </h3>
                            </div>
                        </div>
                    </a>
                </div>

            @endforeach
        </div>


    </div>
@endif
