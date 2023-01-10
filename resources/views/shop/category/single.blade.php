<div>

    <div class="product-box-3 h-100 wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
        <div class="product-header">
            <div class="product-image">
                <a href="/shop/{{$category->slug}}/{{$item['slug']}}">
                    @if($item['photo_id'])
                    <img src="{{_file($item['photo_id'])?->relative}}" class="img-fluid blur-up lazyloaded" alt="{{$item['name']}}">
                        @endif
                </a>
            </div>
        </div>
        <div class="product-footer">
            <div class="product-detail">
                <span class="span-name">{{$item['category']['name']}}</span>
                <a href="/shop/{{$category->slug}}/{{$item['slug']}}">
                    <h5 class="name">{{$item['name']}}</h5>
                </a>

                <h6 class="unit">{{\Illuminate\Support\Str::limit($item['description'], 100)}}</h6>
                <h5 class="price">
                    <span class="theme-color">
                        @if(auth()->guest())
                        ${{moneyFormat($item['msrp'])}}{{$item['type'] == 'services' ? "/mo" : null}}
                        @else
                            ${{$item['type'] == 'services' ? moneyFormat($item['mrc']) . "/mo" : moneyFormat($item['nrc'])}}
                        @endif
                    </span>
                </h5>
                <div class="add-to-cart-box bg-white">
                    <a href="/shop/{{$category->slug}}/{{$item['slug']}}" class="btn btn-add-cart addcart_button addcart-button">View More
                        <i class="fa-solid fa-plus bg-gray"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
