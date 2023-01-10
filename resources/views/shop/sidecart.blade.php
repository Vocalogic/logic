<div>
    @if(cart()->total > 0)
        <div class="pt-25">
            <div class="category-menu">
                <h3>My Cart</h3>

                <ul class="product-list product-right-sidebar border-0 p-0">
                    @foreach(cart()->items as $item)
                        <li>
                            <div class="offer-product">
                                <a href="/shop/{{$item->category->slug}}/{{$item->slug}}" class="offer-image">
                                    @if($item->photo_id && _file($item->photo_id)?->relative)
                                        <img src="{{_file($item->photo_id)->relative}}"
                                             class="img-fluid blur-up lazyloaded"
                                             alt="{{$item->name}}">
                                    @endif
                                </a>

                                <div class="offer-detail">
                                    <div>
                                        <a href="/shop/{{$item->category->slug}}/{{$item->slug}}">
                                            <h6 class="name">
                                                {{$item->qty}} x {{$item->name}}</h6>
                                        </a>
                                        <span>{{\Illuminate\Support\Str::limit($item->description,50)}}
                                            <small>{!! $this->exportAddonText($item->uid) !!} </small>

                                    </span>
                                        <h6 class="price theme-color">${{moneyFormat($item->price)}}
                                            <a style="color:red; display:inline;" wire:click="removeItem('{{$item->uid}}')" href="#">
                                                <i class="fa fa-trash"></i></a>
                                            @if($item->addonTotal > 0)
                                                <small>+${{moneyFormat($item->addonTotal)}}</small>
                                            @endif
                                            </h6>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach

                </ul>


            </div>

            <div class="summery-box p-sticky">
                <div class="summery-contain">
                    <ul class="summery-total">
                        <li class="list-total border-top-0">
                            <h4>Total in Cart</h4>
                            <h4 class="price theme-color">${{moneyFormat($total)}}</h4>
                        </li>
                    </ul>

                    <div class="button-group cart-button">
                        <ul>
                            <li>
                                <button onclick="location.href = '/shop/cart';"
                                        class="btn btn-animation proceed-btn fw-bold">View Cart
                                </button>
                            </li>
                            @if(!isSales())
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
                                            class="btn btn-animation bg-primary fw-bold"><i class="fa fa-user-circle"></i>
                                        Agent Leads
                                    </button>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
