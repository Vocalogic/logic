<li class="right-side">
    <div class="onhover-dropdown header-badge">
        <button type="button" class="btn p-0 position-relative header-wishlist">
            <i data-feather="shopping-cart"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge">{{$count}}
                    <span class="visually-hidden">unread messages</span>
                </span>

        </button>
        <div class="onhover-div">

            @if($total > 0)
                <ul class="cart-list">
                    @foreach($items as $item)
                        @if(!isset($item->category))
                            @continue
                        @endif
                        <li class="product-box-contain">
                            <div class="drop-cart">
                                <a href="/shop/{{$item->category->slug}}/{{$item->slug}}" class="drop-image">
                                    @if($item->photo_id && _file($item->photo_id)?->relative)
                                        <img src="{{_file($item->photo_id)->relative}}"
                                             class="blur-up lazyload" alt="">
                                    @endif
                                </a>

                                <div class="drop-contain">
                                    <a href="/shop/{{$item->category->slug}}/{{$item->slug}}">
                                        <h5>{{$item->qty}} x {{$item->name}}</h5>
                                    </a>
                                    <h6><span></span> ${{moneyFormat($item->price)}}</h6>
                                    <button class="close-button close_button" wire:click="removeItem('{{$item->uid}}')">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            </div>
                        </li>
                    @endforeach

                </ul>

                <div class="price-box">
                    <h5>Total :</h5>
                    <h4 class="theme-color fw-bold">${{moneyFormat($total)}}</h4>
                </div>

                <div class="button-group">
                    <a href="/shop/cart" class="btn btn-sm cart-button">View Cart</a>
                    <a href="/shop/checkout" class="btn btn-sm cart-button theme-bg-color
                                                    text-white">Checkout</a>
                </div>
            @else
                <p>Your cart is currently empty.</p>
            @endif

        </div>
    </div>

</li>
