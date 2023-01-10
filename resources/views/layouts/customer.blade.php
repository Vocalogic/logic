<!doctype html>
<html class="no-js " lang="en" data-theme="{{currentMode()}}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Logic is a VOIP CRM and Process Management Tool provided by Vocalogic">
    <meta name="keyword" content="Vocalogic, Logic, vCRM">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{$title ?? "Title"}}</title>
    @if(setting('brandImage.icon'))
        <link rel="icon" href="{{_file(setting('brandImage.icon'))->relative}}" type="image/png"> <!-- Favicon-->
    @endif
    <!-- plugin css file  -->

    <link rel="stylesheet" href="/assets/css/dataTables.min.css">
    <link rel="stylesheet" href="/assets/css/select2.min.css">
    <link rel="stylesheet" href="/assets/css/x-editable.min.css" />
    <link rel="stylesheet" href="/assets/css/dropify.min.css">
    <link rel="stylesheet" href="/assets/plugin/rating/rating.css"/>
    <link rel="stylesheet" href="/assets/js/wm/waitMe.css">
    <link rel="stylesheet" href="/assets/css/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/css/fancybox.min.css">
    <link rel="stylesheet" href="/assets/css/jquerysteps.min.css">
    <link rel="stylesheet" href="/assets/css/dataTables.min.css">
    <link rel="stylesheet" href="/assets/css/fullcalendar.min.css">
    <link rel="stylesheet" href="/assets/js/jquery.signaturepad.css">


    <!-- project css file  -->
    <link rel="stylesheet" href="/assets/css/luno.style.min.css">
    <link rel="stylesheet" href="/assets/css/logic.css">
    <link rel="stylesheet" href="/assets/css/bootstrapdatepicker.min.css">



</head>

<body class="layout-{{$layout ?? 1}}" data-luno="theme-{{setting('brand.theme')}}">

<!-- start: sidebar -->
<div class="sidebar p-2 py-md-3">
    <div class="container-fluid">
        <!-- sidebar: title-->
        <div class="title-text d-flex align-items-center mb-4 mt-1">
            <h4 class="sidebar-title mb-0 flex-grow-1"><span class="sm-txt">L</span><span>OGIC</span></h4>
        </div>
        <!-- sidebar: Create new -->
        @include('admin.partials.core.customer_menu')
    </div>

    <!-- start: body area -->
    <div class="wrapper">

        <!-- start: page header -->
        <header class="page-header sticky-top px-xl-4 px-sm-2 px-0 py-lg-2 py-1">
            <div class="container-fluid">

                <nav class="navbar">
                    <!-- start: toggle btn -->
                    <div class="d-flex">
                        <button type="button" class="btn btn-link d-none d-xl-block sidebar-mini-btn p-0 text-primary">
                            <span class="hamburger-icon">
                                <span class="line"></span>
                                <span class="line"></span>
                                <span class="line"></span>
                            </span>
                        </button>
                        <button type="button" class="btn btn-link d-block d-xl-none menu-toggle p-0 text-primary">
                            <span class="hamburger-icon">
                                <span class="line"></span>
                                <span class="line"></span>
                                <span class="line"></span>
                            </span>
                        </button>

                    </div>
                    <!-- start: search area -->
                    <div class="header-left flex-grow-1 d-none d-md-block">
                    </div>
                    <!-- start: link -->
                    <ul class="header-right justify-content-end d-flex align-items-center mb-0">
                        <!-- start: notifications dropdown-menu -->

                        <!-- start: Language dropdown-menu -->


                        <!-- start: My notes toggle modal -->

                        <!-- start: quick light dark -->
                        <li>
                            <a class="nav-link quick-light-dark" href="/mode/toggle">
                                <svg viewBox="0 0 16 16" width="18px" fill="currentColor"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278zM4.858 1.311A7.269 7.269 0 0 0 1.025 7.71c0 4.02 3.279 7.276 7.319 7.276a7.316 7.316 0 0 0 5.205-2.162c-.337.042-.68.063-1.029.063-4.61 0-8.343-3.714-8.343-8.29 0-1.167.242-2.278.681-3.286z"/>
                                    <path class="fill-secondary"
                                          d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z"/>
                                </svg>
                            </a>
                        </li>
                        <!-- start: User dropdown-menu -->
                        <li>
                            <div class="dropdown morphing scale-left user-profile mx-lg-3 mx-2">
                                <a class="nav-link dropdown-toggle rounded-circle after-none p-0" href="#" role="button"
                                   data-bs-toggle="dropdown">
                                    <img class="avatar img-thumbnail rounded-circle shadow"
                                         src="{{user()->avatar}}" alt="">
                                </a>
                                <div class="dropdown-menu border-0 rounded-4 shadow p-0">
                                    <div class="card border-0 w240">
                                        <div class="card-body border-bottom d-flex">
                                            <img class="avatar rounded-circle" src="{{user()->avatar}}" alt="">
                                            <div class="flex-fill ms-3">
                                                <h6 class="card-title mb-0">{{user()->name}}</h6>
                                                <span class="text-muted">{{user()->account->name}}</span>
                                            </div>
                                        </div>
                                        <div class="list-group m-2 mb-3">
                                            <a class="list-group-item list-group-item-action border-0"
                                               href="/c/profile"><i class="w30 fa fa-user"></i>My Profile</a>
                                            @if(session('OLD_UID'))
                                                <a class="list-group-item list-group-item-action border-0"
                                                   href="/unshadow"><i class="w30 fa fa-arrow-left"></i>Back to Admin</a>
                                            @endif


                                        </div>
                                        <a href="/logout"
                                           class="btn bg-secondary text-light text-uppercase rounded-0">Logout</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <!-- start: Settings toggle modal -->

                    </ul>
                </nav>

            </div>
        </header>

        <!-- start: page toolbar -->
        <div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
            <div class="container-fluid">

                @if(isset($crumbs))

                    <div class="row mb-3 align-items-center">
                        <div class="col">
                            <ol class="breadcrumb bg-transparent mb-0">
                                <li class="breadcrumb-item"><a href="/">Home</a></li>
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
                        </div>
                    </div> <!-- .row end -->
                @endif
                @yield('pre')
            </div>
        </div>

        <!-- start: page body -->
        @if(isset($submenu))
            <div class="page-body body-layout-1">
                <div class="d-flex flex-nowrap">
                    <div class="order-1 custom_scroll">
                        @yield('submenu')
                    </div>
                    <div class="order-2 flex-grow-1 px-md-3 px-0 custom_scroll">
                        <div class="container-fluid">
                            <div class="col-lg-12">
                                @if($errors->any())
                                    <div role="alert" class="alert alert-danger">
                                        @foreach($errors->all() as $error)
                                            {{$error}} <br>
                                        @endforeach
                                    </div>
                                @endif

                                @if (session()->has('message'))
                                    <div role="alert" class="alert bg-{{bm()}}success">
                                        {{ session('message') }}
                                    </div>
                                @endif

                                @if (session()->has('error'))
                                    <div role="alert" class="alert bg-{{bm()}}danger">
                                        {{ session('error') }}
                                    </div>
                                @endif



                            </div>
                            @yield('content')
                            @include('layouts.footer')
                        </div>
                    </div>

                    @else
                        <div class="page-body px-xl-4 px-sm-2 px-0 py-lg-2 py-1 mt-3">
                            <div class="container-fluid">
                                <div class="col-lg-12">
                                    @if($errors->any())
                                        <div role="alert" class="alert alert-danger">
                                            @foreach($errors->all() as $error)
                                                {{$error}} <br>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if (session()->has('message'))
                                        <div role="alert" class="alert bg-{{bm()}}success">
                                            {{ session('message') }}
                                        </div>
                                    @endif

                                    @if (session()->has('error'))
                                        <div role="alert" class="alert bg-{{bm()}}danger">
                                            {{ session('error') }}
                                        </div>
                                    @endif
                                </div>
                                @yield('content')

                            </div>
                        </div>

                        @include('layouts.footer')
                    @endif
                </div>
                @yield('right')

            </div>

            <div class="modal fade" id="liveModal" tabindex="-1" role="dialog" aria-labelledby="liveModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"></h5>
                        </div>
                        <div class="modal-body">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jquery Core Js -->
            <script src="/assets/js/plugins.js"></script>

            <!-- Plugin Js -->
            <script src="/assets/bundles/dataTables.bundle.js"></script>
            <script src="/assets/bundles/select2.bundle.js"></script>
            <script src="/assets/bundles/bootstraptagsinput.bundle.js"></script>
            <script src="/assets/bundles/bootstrapdatepicker.bundle.js"></script>
            <script src="/assets/bundles/x-editable.bundle.js"></script>
            <script src="/assets/bundles/dropify.bundle.js"></script>
            <script src="/assets/bundles/rating.bundle.js"></script>
            <script src="/assets/js/wm/waitMe.min.js"></script>
            <script src="/assets/bundles/daterangepicker.bundle.js"></script>
            <script src="/assets/bundles/fancybox.bundle.js"></script>
            <script src="/assets/bundles/jquerysteps.bundle.js"></script>
            <script src="/assets/bundles/dataTables.bundle.js"></script>
            <script src="/assets/bundles/sweetalert2.bundle.js"></script>
            <script src="/assets/js/jquery.signaturepad.min.js"></script>
            <script src="/assets/bundles/apexcharts.bundle.js"></script>
            <script src="/assets/bundles/fullcalendar.bundle.js"></script>


            @livewireScripts
            <script type="text/javascript" src="{{mix('/js/app.js')}}"></script>
@yield('javascript')


</body>
</html>
