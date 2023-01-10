@extends('layouts.shop.main', ['title' => "Welcome to " . setting('brand.name')])

@section('content')

    <section class="home-section-2 home-section-bg pt-0 overflow-hidden">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-12">
                    <div class="slider-animate">
                        <div>
                            <div class="home-contain rounded-0 p-0">
                                @if(!setting('shop.hero'))
                                    <img src="/ec/assets/images/grocery/banner/1.jpg"
                                         class="img-fluid bg-img blur-up lazyload" alt="">
                                @else
                                    <img src="{{_file((int)setting('shop.hero'))?->relative}}"
                                         class="img-fluid bg-img blur-up lazyload" alt="{{setting('brand.name')}}"
                                         style="opacity: 0.1;">
                                @endif

                                <div
                                    class="home-detail home-big-space p-center-left home-overlay position-relative">
                                    <div class="container-fluid-lg">
                                        <div>
                                            @if(setting('shop.small_header'))
                                                <h6 class="ls-expanded theme-color text-uppercase">{{setting('shop.small_header')}}
                                                </h6>
                                            @endif
                                            @if(setting('shop.large_header'))
                                                <h1 class="heding-2"
                                                    style="color: {{setting('shop.header_color')}};">{{setting('shop.large_header')}}</h1>
                                            @endif
                                            @if(setting('shop.header_detail'))
                                                <h2 class="content-2"
                                                    style="color: {{setting('shop.header_color')}};">{{setting('shop.header_detail')}}</h2>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    @if(\App\Models\PackageBuild::where('active', true)->count())
        @include('shop.packages')
    @endif


    <section class="category-section-3">
        <div class="container-fluid-lg">
            <div class="title">
                <h2>Browse By Category</h2>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="category-slider-1 arrow-slider wow fadeInUp">
                        @foreach(\App\Models\BillCategory::where('shop_show', true)->whereNotNull('photo_id')->get() as $cat)

                            <div>
                                <div class="category-box-list">
                                    <a href="/shop/{{$cat->slug}}" class="category-name">
                                        <h4>{{$cat->shop_name}}</h4>
                                        <h6>{{$cat->items()->where('shop_show', true)->count()}} items</h6>
                                    </a>
                                    <a href="/shop/{{$cat->slug}}">
                                        <img src="{{_file($cat->photo_id)->relative}}" width="130"
                                             class="img-fluid blur-up lazyload" alt="{{$cat->shop_name}} Products">
                                        <button onclick="location.href = '/shop/{{$cat->slug}}';"
                                                class="btn shop-button">Browse <i class="fas fa-angle-right"></i>
                                        </button>
                                    </a>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </section>

    @if(setting('shop.showCategories') == 'Yes')
        @foreach(\App\Models\BillCategory::where('shop_show', true)->get() as $category)
            @include('shop.category_group', ['category' => $category])
        @endforeach
    @endif

@endsection
