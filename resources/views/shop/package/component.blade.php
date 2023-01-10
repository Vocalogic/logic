<div>
    @if($errorMessage)
        <div class="alert alert-danger">
            {{$errorMessage}}
        </div>
    @endif

    <section class="checkout-section section-b-space">
        <div class="container-fluid-lg">
            <div class="row g-sm-4 g-3">
                <div class="col-xxl-3 col-lg-4">
                    @include('shop.package.nav')
                </div>
                <div class="col-xxl-6 col-lg-8">
                    <div class="tab-content">
                        @include("shop.package.content")
                        @if($step != sizeOf($steps)-1)
                            <a class="btn bg-primary text-white" wire:click="nextStep">Next Page ></a>
                        @else
                            <a class="btn bg-primary text-white" href="/shop/cart">View My Cart</a>
                        @endif
                    </div>
                </div>
                <div class="col-xxl-3 col-lg-8">
                    @livewire('shop.cart-icon-component', ['mini' => false])
                </div>
            </div>

        </div>
    </section>


</div>
