<!-- start: page footer -->
<footer class="page-footer px-xl-4 px-sm-2 px-0 py-3">
    <div class="container-fluid d-flex flex-wrap justify-content-between align-items-center">
        <p class="col-md-4 mb-0 text-muted">© {{date("Y")}} <a href="{{setting('brand.url')}}" target="_blank"
                                                               title="{{setting('brand.name')}}">{{setting('brand.name')}}</a> |
            @if(isAdmin())
                <a href="/admin/versions">Logic v{{setting('version')}}</a>
            @else
                Logic v{{setting('version')}}
            @endif

        </p>
        <a href="#" class="col-md-4 d-flex align-items-center justify-content-center my-3 my-lg-0 me-lg-auto">

            @if(currentMode() == 'light')
                @if(setting('brandImage.light'))
                    <img src="{{_file(setting('brandImage.light'))?->relative}}" width="200">
                @endif

            @else
                @if(setting('brandImage.dark'))
                    <img src="{{_file(setting('brandImage.dark'))?->relative}}" width="200">
                @endif
            @endif

        </a>

    </div>
</footer>
