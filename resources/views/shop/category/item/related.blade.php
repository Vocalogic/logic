@if(count($item->getRelatedItems()))
    <section class="product-list-section section-b-space">
        <div class="container-fluid-lg">
            <div class="title">
                <h2>Related to {{$item->name}}</h2>
                <span class="title-leaf">
                    <svg class="icon-width">
                        <use xlink:href="/ec/assets/svg/leaf.svg#leaf"></use>
                    </svg>
                </span>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="slider-6_1 product-wrapper">
                        @foreach($item->getRelatedItems() as $i)
                            @if(!$i->shop_show)
                                @continue
                            @endif
                            <div>
                                <div class="product-box-3 wow fadeInUp">
                                    <div class="product-header">
                                        <div class="product-image">
                                            <a href="/shop/{{$i->category->slug}}/{{$i->slug}}">
                                                @if($i->photo_id)

                                                    <img src="{{_file($i->photo_id)->relative}}"
                                                         class="img-fluid blur-up lazyload" alt="{{$i->name}}">
                                                @endif
                                            </a>
                                        </div>
                                    </div>

                                    <div class="product-footer">
                                        <div class="product-detail">
                                            <span class="span-name">{{$i->category->name}}</span>
                                            <a href="/shop/{{$i->category->slug}}/{{$i->slug}}">
                                                <h5 class="name">{{$i->name}}</h5>
                                            </a>
                                            <h6 class="unit">{{$i->description}}</h6>
                                            <h5 class="price"><span
                                                    class="theme-color">${{moneyFormat($i->msrp)}}</span>
                                            </h5>
                                            <div class="add-to-cart-box bg-white">
                                                <a href="/shop/{{$i->category->slug}}/{{$i->slug}}"
                                                   class="btn btn-add-cart addcart_button addcart-button">View Details
                                                    <i class="fa-solid fa-magnifying-glass bg-gray"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach


                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
