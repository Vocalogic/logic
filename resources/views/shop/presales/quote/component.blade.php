<div>

    <section class="cart-section section-b-space">
        <div class="container-fluid-lg">
            <div class="row g-sm-5 g-3">
                <div class="col-xxl-9">
                    @if(count($quote->services) || $quote->items()->whereNotNull('frequency')->count())
                        @include('shop.presales.quote.services')
                    @endif


                    @if(count($quote->products))
                        @include('shop.presales.quote.products')
                    @endif


                </div>

                <div class="col-xxl-3">
                    <div class="summery-box p-sticky">
                        <div class="summery-header">
                            <h3>Quote #{{$quote->id}}</h3>
                        </div>
                        <p class="card-text p-3"><b>Note:</b> This custom quote has been created specifically for you. If you remove
                            items from the quote, you will not be able to re-apply at the custom pricing given and must contact your sales agent.</p>


                        <div class="summery-contain">

                            <ul>
                                @if(count($quote->services) || $quote->items()->where('payments', '>', 0)->count())
                                    <li>
                                        <h4>Monthly Services</h4>
                                        <h4 class="price">${{moneyFormat($quote->mrr)}}</h4>
                                    </li>
                                @endif
                                @if(count($quote->products))
                                    <li>
                                        <h4>One-Time Purchases</h4>
                                        <h4 class="price">${{moneyFormat($quote->nrc)}}</h4>
                                    </li>
                                @endif

                                <li>
                                    <h4>Service Term</h4>
                                    <h4 class="price">{{$quote->term ? $quote->term . " Months" : "Month-to-Month"}}</h4>
                                </li>


                            </ul>
                        </div>

                        <ul class="summery-total">
                            <li class="list-total border-top-0">
                                <h4>Total at Checkout</h4>
                                <h4 class="price theme-color">${{moneyFormat($quote->total)}}</h4>
                            </li>
                        </ul>

                        <div class="button-group cart-button">
                            <ul>
                                <li>
                                    @if($quote->lead)
                                    <button onclick="location.href = '/shop/presales/{{$quote->lead->hash}}/{{$quote->hash}}/checkout';"
                                            class="btn btn-animation proceed-btn fw-bold">Proceed To Checkout
                                    </button>
                                        @else
                                        <button onclick="location.href = '/shop/checkout';"
                                                class="btn btn-animation proceed-btn fw-bold">Proceed To Checkout
                                        </button>
                                        @endif
                                </li>

                                <li>
                                    <button onclick="location.href = '/shop';" class="btn btn-light shopping-button text-dark">
                                        <i class="fa-solid fa-arrow-left-long"></i>Continue Shopping</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
