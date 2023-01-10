<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <x-seo::meta />

@if(setting('brandImage.icon'))
        <link rel="icon" href="{{_file(setting('brandImage.icon'))->relative}}" type="image/png"> <!-- Favicon-->
    @endif

    <!-- Google font -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Russo+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@400;500;600;700;800;900&display=swap"
          rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- bootstrap css -->
    <link id="rtl-link" rel="stylesheet" type="text/css" href="/ec/assets/css/vendors/bootstrap.css">

    <!-- font-awesome css -->
    <link rel="stylesheet" type="text/css" href="/ec/assets/css/vendors/font-awesome.css">

    <!-- feather icon css -->
    <link rel="stylesheet" type="text/css" href="/ec/assets/css/vendors/feather-icon.css">

    <!-- slick css -->
    <link rel="stylesheet" type="text/css" href="/ec/assets/css/vendors/slick/slick.css">
    <link rel="stylesheet" type="text/css" href="/ec/assets/css/vendors/slick/slick-theme.css">

    <!-- Iconly css -->
    <link rel="stylesheet" type="text/css" href="/ec/assets/css/bulk-style.min.css">
    <link rel="stylesheet" href="/assets/css/dropify.min.css">

    <!-- Template css -->
    <link id="color-link" rel="stylesheet" type="text/css" href="/ec/assets/css/style.css">
    <link rel="stylesheet" href="/assets/js/jquery.signaturepad.css">


    @livewireStyles
    <style>
        .bgtheme {
            background-color: var(--theme-color) !important;
        }

        .btn-success {
            color: var(--theme-color);
            padding: calc(6px + (15 - 6) * ((100vw - 320px) / (1920 - 320))) calc(11px + (20 - 11) * ((100vw - 320px) / (1920 - 320)));
            position: relative;
            border-radius: 5px;
            overflow: hidden;
            z-index: 0;
        }

        .readable {
            color: #4a5568;
            font-size: calc(15px + (17 - 15) * ((100vw - 320px) / (1920 - 320)));
            line-height: calc(25px + (30 - 25) * ((100vw - 320px) / (1920 - 320)));
            margin-bottom: calc(12px + (20 - 12) * ((100vw - 320px) / (1920 - 320)));
        }
    </style>
</head>

@if(setting('shop.ga'))
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-331F229CKD"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', '{{setting('shop.ga')}}');
    </script>
@endif

<body>

<!-- Loader Start -->
<div class="fullpage-loader">
    <span></span>
    <span></span>
    <span></span>
    <span></span>
    <span></span>
    <span></span>
</div>
<!-- Loader End -->

<!-- Header Start -->
@include('layouts.shop.header')
<!-- Header End -->

<!-- mobile fix menu start -->

<!-- mobile fix menu end -->

<!-- Breadcrumb Section Start -->
@if(isset($crumbs))

    <section class="breadscrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadscrumb-contain">
                        <h1 style="font-size: calc(22px + (28 - 22) * ((100vw - 320px) / (1920 - 320)));">{{$title}}</h1>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                @foreach($crumbs as $url => $crumb)
                                    @if(empty($url))
                                        <li class="breadcrumb-item active" aria-current="page">{{$crumb}}</li>
                                    @else
                                        <li class="breadcrumb-item"><a class="text-secondary"
                                                                       href="{{$url}}">{{$crumb}}</a>
                                        </li>
                                    @endif
                                @endforeach
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
<!-- Breadcrumb Section End -->

@if (session()->has('error'))
    <div role="alert alert-danger">
        {!!  session('error') !!}
    </div>
@endif

@if($errors->any())
    <div role="alert" class="alert alert-danger">
        @foreach($errors->all() as $error)
            {{$error}} <br>
        @endforeach
    </div>
@endif

@yield('content')


<!-- Footer Section Start -->
@include('layouts.shop.footer')
<!-- Footer Section End -->


<!-- Tap to top start -->
<div class="theme-option" style="right: calc(10px + (175 - 10) * ((100vw - 320px) / (1920 - 320)))">
    <div class="back-to-top">
        <a id="back-to-top" href="#">
            <i class="fas fa-chevron-up"></i>
        </a>
    </div>
</div>
<!-- Tap to top end -->

<!-- Bg overlay Start -->
<div class="bg-overlay"></div>
<!-- Bg overlay End -->

<!-- Add address modal box start -->
<div class="modal fade theme-modal" id="liveModal" tabindex="-1" aria-labelledby="liveLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="liveLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<div class="modal fade theme-modal" id="cartMetaModal" tabindex="-1" aria-labelledby="cartMetaLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="liveLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<!-- Add address modal box end -->


<!-- latest jquery-->
<script src="/ec/assets/js/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<!-- jquery ui-->
<script src="/ec/assets/js/jquery-ui.min.js"></script>

<!-- Bootstrap js-->
<script src="/ec/assets/js/bootstrap/bootstrap.bundle.min.js"></script>
<script src="/ec/assets/js/bootstrap/bootstrap-notify.min.js"></script>
<script src="/ec/assets/js/bootstrap/popper.min.js"></script>

<!-- feather icon js-->
<script src="/ec/assets/js/feather/feather.min.js"></script>
<script src="/ec/assets/js/feather/feather-icon.js"></script>

<!-- Lazyload Js -->
<script src="/ec/assets/js/lazysizes.min.js"></script>

<!-- Wizard js -->
<script src="/ec/assets/js/wizard.js"></script>

<!-- Slick js-->
<script src="/ec/assets/js/slick/slick.js"></script>
<script src="/ec/assets/js/slick/slick-animation.min.js"></script>
<script src="/ec/assets/js/custom-slick-animated.js"></script>
<script src="/ec/assets/js/slick/custom_slick.js"></script>
<script src="/assets/bundles/sweetalert2.bundle.js"></script>
<script src="/assets/bundles/dropify.bundle.js"></script>


<!-- Quantity js -->
<script src="/ec/assets/js/quantity.js"></script>

<!-- script js -->

<script src="/ec/assets/js/script.js"></script>
<script src="/ec/assets/js/lusqsztk.js"></script>
<script src="/assets/js/jquery.signaturepad.min.js"></script>

<script src="/ec/assets/js/wow.min.js"></script>
<script src="/ec/assets/js/custom-wow.js"></script>
@livewireScripts
<script type="text/javascript" src="{{mix('/js/cart.js')}}"></script>
@if(setting('shop.tawk'))
    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
        var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
        (function () {
            var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = '{{setting('shop.tawk_embed')}}';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>
    <!--End of Tawk.to Script-->
@endif

@if(setting('shop.color'))
    <script>
        document.body.style.setProperty("--theme-color", '{{setting('shop.color')}}');
        @if(setting('shop.color2'))
        document.body.style.setProperty("--theme-color2", '{{setting('shop.color2')}}');
        @endif
    </script>
@endif
@if(setting('shop.mode') == 'dark')
    <script>
        $("body").removeClass("light");
        $("body").addClass("dark");
        document
            .getElementById("color-link")
            .setAttribute("href", "/ec/assets/css/dark.css");
    </script>
@endif

@yield('javascript')
@if(session('message'))

    <script>
        let Toast = Swal.mixin({
            toast: true,
            position: 'center',
            showConfirmButton: false,
            timer: 5000
        });
        Toast.fire({
            text: "{{session('message')}}",
            title: "Notification Message",
            icon: "info",
        });
    </script>
@endif

</body>

</html>
