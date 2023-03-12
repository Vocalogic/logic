<header class="pb-md-4 pb-0">
    <div class="header-top">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-xxl-3 d-xxl-block d-none">

                </div>

                <div class="col-xxl-6 col-lg-9 d-lg-block d-none">
                    @php
                        $lines = explode("\n", setting('shop.ticker'));
                    @endphp
                    @if(count($lines))
                    <div class="header-offer">
                        <div class="notification-slider">

                            @foreach($lines as $line)
                            <div>
                                <div class="timer-notification">
                                    <h6>{!! $line !!}</h6>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                    </div>
                </div>


            </div>
        </div>
    </div>

    <div class="top-nav top-header sticky-header">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="navbar-top">
                        <button class="navbar-toggler d-xl-none d-inline navbar-menu-button" type="button"
                                data-bs-toggle="offcanvas" data-bs-target="#primaryMenu">
                                <span class="navbar-toggler-icon">
                                    <i class="fa-solid fa-bars"></i>
                                </span>
                        </button>
                        <a href="/shop" class="web-logo nav-logo">
                            @if(setting('brandImage.light'))
                                <img src="{{_file(setting('brandImage.light'))?->relative}}" class="img-fluid blur-up lazyload" alt="{{setting('brand.name')}}">
                            @endif
                        </a>

                        <div class="middle-box">


                            <div class="search-box">
                                <div class="input-group">
                                    <input type="search" class="form-control" placeholder="I'm searching for..."
                                           aria-label="Recipient's username" aria-describedby="button-addon2">
                                    <button class="btn bg-theme" type="button" id="button-addon2">
                                        <i data-feather="search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="rightside-box">
                            <div class="search-full">
                                <div class="input-group">
                                        <span class="input-group-text">
                                            <i data-feather="search" class="font-light"></i>
                                        </span>
                                    <input type="text" class="form-control search-type" placeholder="Search here..">
                                    <span class="input-group-text close-search">
                                            <i data-feather="x" class="font-light"></i>
                                        </span>
                                </div>
                            </div>
                            <ul class="right-side-menu">
                                <li class="right-side">
                                    <div class="delivery-login-box">
                                        <div class="delivery-icon">
                                            <div class="search-box">
                                                <i data-feather="search"></i>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="right-side">
                                    <a href="#" class="delivery-login-box">
                                        <div class="delivery-icon">
                                            <i data-feather="phone-call"></i>
                                        </div>
                                        <div class="delivery-detail">
                                            <h6>Call Us!</h6>
                                            <h5>{{setting('shop.contact')}}</h5>
                                        </div>
                                    </a>
                                </li>
                                <li class="right-side">
                                        <a class="live" href="/shop/authorize" data-title="Request Assistance"
                                           class="btn p-0 position-relative header-wishlist">
                                            <i data-feather="help-circle"></i>
                                        </a>
                                </li>
                                @livewire('shop.cart-icon-component')

                                @livewire('shop.shop-assist-component')

                                <li class="right-side onhover-dropdown">
                                    <div class="delivery-login-box">
                                        <div class="delivery-icon">
                                            <i data-feather="user"></i>
                                        </div>
                                        <div class="delivery-detail">
                                            <h6>Hello,</h6>
                                            <h5>{{auth()->user() ? auth()->user()->name : "Guest"}}</h5>
                                        </div>
                                    </div>

                                    <div class="onhover-div onhover-div-login">
                                        <ul class="user-box-name">
                                            @if(auth()->guest())
                                                <li class="product-box-contain">
                                                    <i></i>
                                                    <a href="/login">Log In</a>
                                                </li>
                                                <li class="product-box-contain">
                                                    <a href="/forgot">Forgot Password</a>
                                                </li>
                                            @else
                                                <li class="product-box-contain">
                                                    <i></i>
                                                    <a href="/shop/account">My Account</a>
                                                </li>
                                                <li class="product-box-contain">
                                                    <i></i>
                                                    <a href="/shop/logout">Logout</a>
                                                </li>
                                            @endif

                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid-lg">
        <div class="row">
            <div class="col-12">
                <div class="header-nav">
                    <div class="header-nav-left">
                        <button class="dropdown-category">
                            <i data-feather="align-left"></i>
                            <span>Browse {{setting('brand.name')}}</span>
                        </button>

                        <div class="category-dropdown">
                            <div class="category-title">
                                <h5>Categories</h5>
                                <button type="button" class="btn p-0 close-button text-content">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>

                            <ul class="category-list">
                                @foreach(\App\Models\BillCategory::where('shop_show', true)->orderBy('name')->get() as $category)
                                <li class="onhover-category-list">
                                    <a href="/shop/{{$category->slug}}" class="category-name">
                                        <h6>{{$category->shop_name}}</h6>
                                        <i class="fa-solid fa-angle-right"></i>
                                    </a>
                                </li>
                                @endforeach


                            </ul>
                        </div>
                    </div>

                    <div class="header-nav-middle">
                        <div class="main-nav navbar navbar-expand-xl navbar-light navbar-sticky">
                            <div class="offcanvas offcanvas-collapse order-xl-2" id="primaryMenu">
                                <div class="offcanvas-header navbar-shadow">
                                    <h5>Menu</h5>
                                    <button class="btn-close lead" type="button" data-bs-dismiss="offcanvas"
                                            aria-label="Close"></button>
                                </div>
                                <div class="offcanvas-body">
                                    <ul class="navbar-nav">
                                        <li class="nav-item">
                                            <a class="nav-link nav-link-2" href="/shop">Home</a>
                                        </li>
                                        @if(!auth()->guest() && !isSales())
                                        <li class="nav-item">
                                            <a class="nav-link nav-link-2" href="/shop/account">My Account</a>
                                        </li>
                                        @endif

                                            <li class="nav-item">
                                                <a class="nav-link nav-link-2" href="/shop/cart">My Cart</a>
                                            </li>
                                        @if(isSales())
                                            <li class="nav-item">
                                                <a class="nav-link nav-link-2" href="/sales">Agent Dashboard</a>
                                            </li>
                                            @endif

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="header-nav-right">
                        @if(auth()->guest())
                        <a class="btn deal-button" href="/login">
                            <i data-feather="zap"></i>
                            <span>Customer Login</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>


<div class="mobile-menu d-md-none d-block mobile-cart">
    <ul>
        <li class="active">
            <a href="/shop">
                <i class="iconly-Home icli"></i>
                <span>Home</span>
            </a>
        </li>

        <li class="mobile-category">
            <a href="javascript:void(0)">
                <i class="iconly-Category icli js-link"></i>
                <span>Category</span>
            </a>
        </li>

        <li>
            <a class="search-box live" href="/shop/authorize" data-title="Request Assistance">
                <i class="iconly-Search icli"></i>
                <span>Assistance</span>
            </a>
        </li>


        <li>
            <a href="/shop/cart">
                <i class="iconly-Bag-2 icli fly-cate"></i>
                <span>Cart</span>
            </a>
        </li>
    </ul>
</div>
