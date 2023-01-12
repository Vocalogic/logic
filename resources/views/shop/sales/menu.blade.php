@if(isset($quote))
    @if(cart()->total > 0)
            <a class="btn btn-primary bg-warning btn-sm text-white mb-3" href="/sales/leads/{{$lead->id}}/quotes/{{$quote->id}}/apply">
                <i class="fa fa-user-circle"></i> &nbsp; Apply Cart to Quote</a>
            @endif
            <a class="btn btn-primary bg-primary btn-sm text-white mb-3" href="/sales/leads/{{$lead->id}}/quotes/{{$quote->id}}/presentable">
                @if($quote->presentable)
                    Make Quote Unpresentable
                @else
                    Make Quote Presentable
                @endif
            </a>
    @if(user()->requires_approval && $quote->approved)
            <a class="btn btn-primary bg-info btn-sm text-white mb-3" href="/sales/leads/{{$lead->id}}/quotes/{{$quote->id}}/send">
                <i class="fa fa-mail-forward"></i> &nbsp; Send Quote to Customer</a>
    @endif
            <a class="btn btn-primary bg-primary btn-sm text-white mb-3 wait" data-message="Generating Quote.." href="/sales/leads/{{$lead->id}}/quotes/{{$quote->id}}/download">
                <i class="fa fa-download"></i> &nbsp; Download Quote
            </a>
@endif


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

                        <img src="/ec/assets/images/inner-page/user/1.jpg" class="blur-up update_img lazyloaded" alt="">

                    <div class="cover-icon">
                        <i class="fa-solid fa-pen">
                            <input type="file" onchange="readURL(this,0)">
                        </i>
                    </div>
                </div>
            </div>

            <div class="profile-name">
                <h3>{{setting('brand.name')}}</h3>
                <h6 class="text-content">{{user()->name}}</h6>
            </div>
        </div>
    </div>

    <ul class="nav nav-pills user-nav-pills" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link {{preg_match("/sales$/", app('request')->getUri()) ? "active" : null}}" id="pills-dashboard-tab" aria-controls="pills-dashboard" aria-selected="true" href="/sales"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                Overview</a>
        </li>

        <li class="nav-item" role="presentation">
            <a class="nav-link {{preg_match("/leads/", app('request')->getUri()) ? "active" : null}}" id="pills-order-tab" href="/sales/leads" aria-controls="pills-order" aria-selected="false"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                Leads ({{user()->activeLeads}})</a>
        </li>

        <li class="nav-item" role="presentation">
            <a class="nav-link {{preg_match("/account/", app('request')->getUri()) ? "active" : null}}" id="pills-order-tab" href="/sales/accounts" aria-controls="pills-order" aria-selected="false">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                Accounts ({{user()->activeAccounts}})</a>
        </li>

        <li class="nav-item" role="presentation">
            <a class="nav-link {{preg_match("/commission/", app('request')->getUri()) ? "active" : null}}" href="/sales/commissions" aria-controls="pills-wishlist" aria-selected="false">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shield"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                Commissions</a>
        </li>




        <li class="nav-item" role="presentation">
            <a class="nav-link {{preg_match("/password/", app('request')->getUri()) ? "active" : null}}" href="/shop/account/password" aria-controls="pills-wishlist" aria-selected="false">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>                Change Password</a>
        </li>

    </ul>
</div>
