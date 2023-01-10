<div>

    <div class="row g-sm-5 g-3">
        <div class="col-xxl-{{$mini ? "12" : "9"}}">
            @if(cart()->coupon->id)
                <div class="alert alert-success">
                    <b>Coupon Applied: </b> ({{cart()->coupon->coupon}}) {{cart()->coupon->name}}
                </div>
            @endif
            @if(count($services))
                @include('shop.cart.services')
            @endif


            @if(count($products))
                @include('shop.cart.products')
            @endif

        </div>
        @if(!$mini)
            <div class="col-xxl-3">
                <div class="summery-box p-sticky">
                    <div class="summery-header">
                        <h3>Review Cart Items</h3>
                    </div>
                    <div class="summery-contain">
                        <ul>
                            @if($serviceTotal)
                                <li>
                                    <h4>Monthly Services</h4>
                                    <h4 class="price">${{moneyFormat($serviceTotal)}}</h4>
                                </li>
                            @endif
                            @if($productTotal)
                                <li>
                                    <h4>One-Time Purchases</h4>
                                    <h4 class="price">${{moneyFormat($productTotal)}}</h4>
                                </li>
                            @endif

                            @if($serviceTotal)
                                <li>
                                    <h4>Service Term</h4>
                                    <h4 class="price">Month-to-Month</h4>
                                </li>
                            @endif

                            @if(cart()->coupon && cart()->coupon->id)
                                <li>
                                    <h4>Coupon Applied</h4>
                                    <h4 class="price">{{cart()->coupon->coupon}}</h4>
                                </li>
                            @endif
                        </ul>
                    </div>


                    <ul class="summery-total">
                        <li class="list-total border-top-0">
                            <h4>Total at Checkout</h4>
                            <h4 class="price theme-color">${{moneyFormat($total)}}</h4>
                        </li>
                    </ul>

                    @if($errorMessage)
                        <div class="alert alert-danger">{{$errorMessage}}</div>
                    @endif
                    @if($successMessage)
                        <div class="alert alert-success">{{$successMessage}}</div>
                    @elseif(!cart()->coupon->id)
                        <div class="coupon-cart">
                            <div class="mb-3 coupon-box input-group">
                                <input type="text" class="form-control" wire:model="couponField"
                                       placeholder="Enter Coupon Code...">
                                <button class="btn-apply" wire:click="applyCoupon">Apply</button>
                            </div>
                        </div>
                    @endif

                    <div class="button-group cart-button">
                        <ul>
                            @if(!isSales())
                            <li>
                                <button onclick="location.href = '/shop/checkout';"
                                        class="btn btn-animation proceed-btn fw-bold">Proceed To Checkout
                                </button>
                            </li>

                            <li>
                                <button onclick="location.href = '/shop/quote';"
                                        class="dropdown-category bg-theme w-100 fw-bold"><i class="fa fa-download"></i>
                                    Download Quote
                                </button>
                            </li>
                            @endif
                            @if(isSales())
                                <li>
                                    <button onclick="location.href = '/sales/leads';"
                                            class="btn btn-animation proceed-btn fw-bold"><i class="fa fa-user-circle"></i>
                                        Agent Leads
                                    </button>
                                </li>
                            @endif

                                <li>
                                <button onclick="location.href = '/shop';"
                                        class="btn btn-light shopping-button text-dark">
                                    <i class="fa-solid fa-arrow-left-long"></i>Continue Shopping
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        @endif


    </div>


</div>
