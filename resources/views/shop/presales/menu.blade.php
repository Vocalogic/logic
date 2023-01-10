<div class="dashboard-left-sidebar">
    <div class="close-button d-flex d-lg-none">
        <button class="close-sidebar">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="profile-box">
        <div class="cover-image">
            @if(setting('brandImage.light'))
            <img src="{{_file(setting('brandImage.light'))?->relative}}" class="img-fluid blur-up lazyloaded" alt="">
            @endif
        </div>

        <div class="profile-contain">
            <div class="profile-image">
                <div class="position-relative">
                    @if($lead->logo_id)
                        <img src="{{_file($lead->logo_id)?->relative}}" class="blur-up update_img lazyloaded" alt="">
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
                <h3>{{$lead->company}}</h3>
                <h6 class="text-content">{{$lead->contact}}</h6>
            </div>
        </div>
    </div>

    <ul class="nav nav-pills user-nav-pills" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="pills-dashboard-tab" aria-controls="pills-dashboard" aria-selected="true" href="/shop/presales/{{$lead->hash}}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                Overview</a>
        </li>

        @foreach($lead->quotes()->where('archived', false)->where('presentable', true)->get() as $quote)
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="pills-order-tab" href="/shop/presales/{{$lead->hash}}/{{$quote->hash}}/" aria-controls="pills-order" aria-selected="false"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>Quote #{{$quote->id}}</a>
        </li>
        @endforeach


    </ul>
</div>
