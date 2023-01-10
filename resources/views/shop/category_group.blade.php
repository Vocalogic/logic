<section class="product-section">
    <div class="container-fluid-lg">
        <div class="title">
            <h2>{{$category->name}}</h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="slider-7_1 arrow-slider img-slider">
                    @foreach($category->items()->where('shop_show', true)->get() as $item)
                    <div>
                        <div class="product-box-4 wow fadeInUp">
                            <div class="product-image product-image-2">
                                @if($item->photo_id)
                                <a href="/shop/{{$item->category->slug}}/{{$item->slug}}">
                                    <img src="{{_file($item->photo_id)?->relative}}"
                                         class="img-fluid blur-up lazyload" alt="{{$item->name}}">
                                </a>
                                @endif
                            </div>

                            <div class="product-detail">
                                <a href="/shop/{{$item->category->slug}}/{{$item->slug}}">
                                    <h5 class="name text-title">{{$item->name}}</h5>
                                </a>
                                <h5 class="price theme-color">${{moneyFormat($item->msrp)}}{{$item->type == 'services' ? "/mo" : null}}</h5>
                                <div class="addtocart_btn">
                                    <a href="/shop/{{$item->category->slug}}/{{$item->slug}}" class="add-button add_cart btn buy-button text-light">
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </a>
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
