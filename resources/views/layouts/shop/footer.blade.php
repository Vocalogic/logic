<footer class="section-t-space">
    <div class="container-fluid-lg">

        <div class="main-footer section-b-space section-t-space">
            <div class="row g-md-4 g-3">
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="footer-logo">
                        <div class="theme-logo">
                            <a href="/shop">
                                @if(setting('brandImage.light'))
                                    <img src="{{_file(setting('brandImage.light'))?->relative}}" class="img-fluid blur-up lazyload" alt="{{setting('brand.name')}}">
                                @endif
                            </a>
                        </div>

                        <div class="footer-logo-contain">
                            <p>{{setting('shop.info')}}</p>

                            <ul class="address">
                                <li>
                                    <i data-feather="home"></i>
                                    <a href="javascript:void(0)">{{setting('brand.address')}} {{setting('brand.address2')}}<br/> {{setting('brand.csz')}}</a>
                                </li>
                                <li>
                                    <i data-feather="mail"></i>
                                    <a href="javascript:void(0)">{{setting('shop.email')}}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                    <div class="footer-title">
                        <h4>Categories</h4>
                    </div>

                    <div class="footer-contain">
                        <ul>
                            @foreach(\App\Models\BillCategory::where('shop_show', true)->take(6)->get() as $cat)
                            <li>
                                <a href="/shop/{{$cat->slug}}" class="text-content">{{$cat->shop_name}}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="col-xl col-lg-2 col-sm-3">
                    <div class="footer-title">
                        <h4>Recently Viewed</h4>
                    </div>

                    <div class="footer-contain">
                        <ul>
                            @foreach(\App\Models\BillItem::where('shop_show', true)->orderBy('last_viewed', 'DESC')->take(6)->get() as $item)
                            <li>
                                <a href="/shop/{{$item->category->slug}}/{{$item->slug}}" class="text-content">{{$item->name}}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="col-xl-2 col-sm-3">
                    <div class="footer-title">
                        <h4>Latest Reviews</h4>
                    </div>

                    <div class="footer-contain">
                        <ul>

                        </ul>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="footer-title">
                        <h4>Contact Us</h4>
                    </div>

                    <div class="footer-contact">
                        <ul>
                            <li>
                                <div class="footer-number">
                                    <i data-feather="phone"></i>
                                    <div class="contact-number">
                                        <h6 class="text-content">Order Inquiries:</h6>
                                        <h5>{{setting('shop.contact')}}</h5>
                                    </div>
                                </div>
                            </li>

                            <li>
                                <div class="footer-number">
                                    <i data-feather="mail"></i>
                                    <div class="contact-number">
                                        <h6 class="text-content">Email Address :</h6>
                                        <h5>{{setting('shop.email')}}</h5>
                                    </div>
                                </div>
                            </li>


                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="sub-footer section-small-space">
            <div class="reserve">
                <h6 class="text-content">©{{now()->format("Y")}} {{setting('brand.name')}}, All Rights Reserved</h6>
            </div>


        </div>
    </div>
</footer>
