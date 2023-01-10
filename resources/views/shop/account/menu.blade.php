<div class="dashboard-left-sidebar">
    <div class="close-button d-flex d-lg-none">
        <button class="close-sidebar">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="profile-box">
        <div class="cover-image">
            @if(setting('shop.hero'))
                <img src="{{_file(setting('shop.hero'))->relative}}" class="img-fluid blur-up lazyloaded" alt="">
            @endif
        </div>

        <div class="profile-contain">
            <div class="profile-image">
                <div class="position-relative">
                    @if($account->logo_id)
                        <img src="{{_file($account->logo_id)->relative}}" class="blur-up update_img lazyloaded" alt="">
                    @else
                        <img src="/ec/assets/images/inner-page/user/1.jpg" class="blur-up update_img lazyloaded" alt="">
                    @endif
                    <div class="cover-icon">
                        <i class="fa-solid fa-pen">
                            <input type="file" onchange="readURL(this,0)">
                        </i>
                    </div>
                </div>
            </div>

            <div class="profile-name">
                <h3>{{$account->name}}</h3>
                <h6 class="text-content">{{user()->name}}</h6>
            </div>
        </div>
    </div>

    <ul class="nav nav-pills user-nav-pills" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link {{preg_match("/account$/", app('request')->getUri()) ? "active" : null}}" id="pills-dashboard-tab" aria-controls="pills-dashboard" aria-selected="true" href="/shop/account"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                Overview</a>
        </li>

        <li class="nav-item" role="presentation">
            <a class="nav-link {{preg_match("/order/", app('request')->getUri()) ? "active" : null}}" id="pills-order-tab" href="/shop/account/orders" aria-controls="pills-order" aria-selected="false"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                Orders ({{$account->orders()->where('active', true)->count()}})</a>
        </li>

        <li class="nav-item" role="presentation">
            <a class="nav-link {{preg_match("/profile/", app('request')->getUri()) ? "active" : null}}" id="pills-order-tab" href="/shop/account/profile" aria-controls="pills-order" aria-selected="false">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                Profile</a>
        </li>

        <li class="nav-item" role="presentation">
            <a class="nav-link {{preg_match("/service/", app('request')->getUri()) ? "active" : null}}" href="/shop/account/services" aria-controls="pills-wishlist" aria-selected="false">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shield"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                Services ({{$account->items()->count()}})</a>
        </li>
         <li class="nav-item" role="presentation">
            <a class="nav-link {{preg_match("/quote/", app('request')->getUri()) ? "active" : null}}" href="/shop/account/quotes" aria-controls="pills-wishlist" aria-selected="false">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
                Quotes ({{$account->openQuotes()->count()}})</a>
        </li>

        <li class="nav-item" role="presentation">
            <a class="nav-link {{preg_match("/invoice/", app('request')->getUri()) ? "active" : null}}" href="/shop/account/invoices" aria-controls="pills-wishlist" aria-selected="false">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-credit-card"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                Invoices
                @if($account->account_balance > 0) ({{$account->invoices()->where('status', '!=', 'Draft')->where('status', '!=', 'Paid')->count()}})
                @endif
            </a>
        </li>



        <li class="nav-item" role="presentation">
            <a class="nav-link {{preg_match("/password/", app('request')->getUri()) ? "active" : null}}" href="/shop/account/password" aria-controls="pills-wishlist" aria-selected="false">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>                Change Password</a>
        </li>

    </ul>
</div>
