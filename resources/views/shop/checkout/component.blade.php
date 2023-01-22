<div>
    <section class="checkout-section section-b-space">
        <div class="container-fluid-lg">
            <div class="row g-sm-4 g-3">
                <div class="col-xxl-3 col-lg-4">
                    @include('shop.checkout.checkout_nav')
                </div>
                <div class="col-xxl-9 col-lg-8">
                    <div class="tab-content">
                        @if($errorMessage)
                            <div class="alert alert-danger">
                                {{$errorMessage}}
                            </div>
                        @endif
                        @include("shop.checkout.$stepView")
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
