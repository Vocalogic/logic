<div class="row">
    <div class="col-12">
        <div class="slider-1 slider-animate product-wrapper no-arrow slick-initialized slick-slider slick-dotted">
            <div class="slick-list draggable">
                <div class="slick-track"
                     style="opacity: 1; width: 11053px; transform: translate3d(-1579px, 0px, 0px);">
                    <div class="slick-slide slick-cloned" data-slick-index="-1" id="" aria-hidden="true"
                         style="width: 1579px;" tabindex="-1">
                        <div class="banner-contain-2 hover-effect bg-size blur-up lazyloaded"
                             style="background-image: url({{_file($category->shop_offer_image_id)?->relative}}); background-size: cover; background-position: center center; background-repeat: no-repeat; display: block;">
                            <img src="{{_file($category->shop_offer_image_id)?->relative}}" class="bg-img rounded-3 blur-up lazyload"
                                 alt="" style="display: none;">
                            <div class="banner-detail p-center-right position-relative shop-banner ms-auto banner-small">
                                <div>
                                    <h3>{{$category->shop_offer}}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slick-slide slick-current slick-active" data-slick-index="0"
                         aria-hidden="false" style="width: 1579px;" tabindex="0" role="tabpanel"
                         id="slick-slide00" aria-describedby="slick-slide-control00">
                        <div class="banner-contain-2 hover-effect bg-size blur-up lazyloaded"
                             style="background-image: url({{_file($category->shop_offer_image_id)?->relative}}); background-size: cover; background-position: center center; background-repeat: no-repeat; display: block;">
                            <img src="{{_file($category->shop_offer_image_id)?->relative}}" class="bg-img rounded-3 blur-up lazyload"
                                 alt="" style="display: none;">
                            <div
                                class="banner-detail p-center-right position-relative shop-banner ms-auto banner-small">
                                <div>
                                    <h3>{{$category->shop_offer}}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>



        </div>
    </div>
</div>
