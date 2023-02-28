<!doctype html>
<html lang="en" data-layout-mode="{{currentMode()}}" data-layout="vertical" data-topbar="light" data-sidebar="{{currentMode()}}" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Logic is an open-source ecommerce and billing management platform.">
    <meta name="keyword" content="laravel, open source, ecommerce, billing management platform, free">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{$title ?? "Title"}}</title>
    @if(setting('brandImage.icon'))
        <link rel="icon" href="{{_file(setting('brandImage.icon'))?->relative}}" type="image/png"> <!-- Favicon-->
    @endif
    <!-- Layout config Js -->
    <script src="{{mix('assets/js/layout.js')}}"></script>
    <!-- Bootstrap Css -->
    <link href="{{mix('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
    <!-- Icons Css -->
    <link href="{{mix('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css"/>
    <!-- App Css-->
    <link href="{{mix('css/all.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{mix('css/logic.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{mix('assets/css/app.min.css')}}" rel="stylesheet" type="text/css"/>

    @livewireStyles

</head>

<body>

<!-- Begin page -->
<div id="layout-wrapper">

    <header id="page-topbar">
        <div class="layout-width">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- LOGO -->
                    <div class="navbar-brand-box horizontal-logo">
                        <a href="/" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="/assets/images/logo-sm.png" alt="" height="22">
                        </span>
                            <span class="logo-lg">
                            <img src="/assets/images/logo-dark.png" alt="" height="17">
                        </span>
                        </a>

                        <a href="/" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="/assets/images/logo-sm.png" alt="" height="22">
                        </span>
                            <span class="logo-lg">
                            <img src="/assets/images/logo-light.png" alt="" height="17">
                        </span>
                        </a>
                    </div>

                    <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger"
                            id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                    </button>

                    <!-- App Search-->
                    @livewire('admin.search-component')

                </div>

                <div class="d-flex align-items-center">

                    <div class="dropdown d-md-none topbar-head-dropdown header-item">
                        <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                                id="page-header-search-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                            <i class="bx bx-search fs-22"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                             aria-labelledby="page-header-search-dropdown">
                            <form class="p-3">
                                <div class="form-group m-0">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search ..."
                                               aria-label="Recipient's username">
                                        <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>






                    <div class="ms-1 header-item d-none d-sm-flex">
                        <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                                data-toggle="fullscreen">
                            <i class='bx bx-fullscreen fs-22'></i>
                        </button>
                    </div>


                    @if(isset($log))
                    <div class="ms-1 header-item d-none d-sm-flex">
                        <a type="button" data-title="Log Viewer"
                           data-bs-toggle="tooltip" data-bs-placement="left" title="Log Viewer"
                           class="live btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" data-size="modal-xl"
                           href="/admin/logs/{{$log['type']}}/{{$log['id']}}">
                            <i class='bx bx-customize fs-22'></i>
                        </a>
                    </div>
                    @endif

                    @if(isset($docs))
                        <div class="ms-1 header-item d-none d-sm-flex">
                            <a type="button" target="_blank"
                               data-bs-toggle="tooltip" data-bs-placement="left" title="Documentation"
                               class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" data-size="modal-xl"
                               href="{{$docs}}">
                                <i class='bx bx-layout fs-22'></i>
                            </a>
                        </div>
                    @endif

                    @if(isset($video))
                        <div class="ms-1 header-item d-none d-sm-flex">
                            <a type="button" target="_blank"
                               data-bs-toggle="tooltip" data-bs-placement="left" title="Video Tutorial"
                               class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" data-size="modal-xl"
                               href="{{$video}}">
                                <i class='bx bx-video-plus fs-22'></i>
                            </a>
                        </div>
                    @endif


                    <div class="ms-1 header-item d-none d-sm-flex">
                        <a type="button" href="/mode/toggle"
                           data-bs-toggle="tooltip" data-bs-placement="left" title="Switch to {{currentMode() == 'dark' ? "light" : "dark"}} mode"
                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle">
                            <i class='bx bx-moon fs-22'></i>
                        </a>
                    </div>



                    <div class="dropdown ms-sm-3 header-item topbar-user">
                        <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user" src="{{user()->avatar}}"
                                 alt="Header Avatar">
                            <span class="text-start ms-xl-2">
                                <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">{{user()->name}}</span>
                                <span class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text">{{user()->account->name}}</span>
                            </span>
                        </span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- item-->
                            <h6 class="dropdown-header">Welcome {{user()->first}}!</h6>
                            <a class="dropdown-item" href="/admin/profile"><i
                                    class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
                                    class="align-middle">Profile</span></a>
                            <a class="dropdown-item" href="/logout"><i
                                    class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span
                                    class="align-middle" data-key="t-logout">Logout</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- removeNotificationModal -->
    <div id="removeNotificationModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            id="NotificationModalbtn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="mt-2 text-center">
                        <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                                   colors="primary:#f7b84b,secondary:#f06548"
                                   style="width:100px;height:100px"></lord-icon>
                        <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                            <h4>Are you sure ?</h4>
                            <p class="text-muted mx-4 mb-0">Are you sure you want to remove this Notification ?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn w-sm btn-danger" id="delete-notification">Yes, Delete It!
                        </button>
                    </div>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- ========== App Menu ========== -->
    <div class="app-menu navbar-menu">
        <!-- LOGO -->
        <div class="navbar-brand-box">
            <!-- Dark Logo-->
            <a href="index.html" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="/assets/images/logo-sm.png" alt="" height="22">
                    </span>
                <span class="logo-lg">
                        <img src="/assets/images/logo-dark.png" alt="" height="17">
                    </span>
            </a>
            <!-- Light Logo-->
            <a href="index.html" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="/assets/images/logo-sm.png" alt="" height="22">
                    </span>
                <span class="logo-lg">
                        <img src="/assets/images/logo-light.png" alt="" height="17">
                    </span>
            </a>
            <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
                    id="vertical-hover">
                <i class="ri-record-circle-line"></i>
            </button>
        </div>

        <div id="scrollbar">
            <div class="container-fluid">

                <div id="two-column-menu">
                </div>
                <ul class="navbar-nav" id="navbar-nav">
                        @include('admin.partials.core.admin_menu')
                </ul>
            </div>
            <!-- Sidebar -->
        </div>

        <div class="sidebar-background"></div>
    </div>
    <!-- Left Sidebar End -->
    <!-- Vertical Overlay-->
    <div class="vertical-overlay"></div>

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">{{$title}}</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
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

                        </div>
                    </div>
                </div>
                <!-- end page title -->
                @if($errors->any())
                    <div role="alert" class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            {!! $error !!}<br/>
                        @endforeach
                    </div>
                @endif

                @if (session()->has('message'))
                    <div class="toasted" data-message="{{session('message')}}"
                         data-title="Operation Successful" data-icon="success"></div>
                @endif

                @if (session()->has('error'))
                    <div role="alert"
                         class="alert alert-danger">
                        {!!  session('error') !!}
                    </div>
                @endif
                @yield('content')





            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        &copy;{{now()->year}} {{setting('brand.name')}}
                    </div>
                    <div class="col-sm-6">
                        <div class="text-sm-end d-none d-sm-block">

                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <!-- end main content-->

</div>
<!-- END layout-wrapper -->


<!--start back-to-top-->
<button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
    <i class="ri-arrow-up-line"></i>
</button>
<!--end back-to-top-->

<!--preloader-->
<div id="preloader">
    <div id="status">
        <div class="spinner-border text-primary avatar-sm" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>

<div class="modal fade" id="liveModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="liveModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="liveLeft" tabindex="-1">
    <div class="modal-dialog modal-dialog-vertical modal-dialog-scrollable">
        <div class="modal-content rounded-0">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
            </div>
            <div class="modal-body custom_scroll">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="liveRight" tabindex="-1">
    <div class="modal-dialog modal-dialog-vertical right-side modal-dialog-scrollable">
        <div class="modal-content rounded-0">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
            </div>
            <div class="modal-body custom_scroll">
            </div>
        </div>
    </div>
</div>



<!-- JAVASCRIPT -->
<script src="/assets/libs/bootstrap/bootstrap.min.js"></script>
<script src="/assets/libs/simplebar/simplebar.min.js"></script>
<script src="/assets/libs/node-waves/node-waves.min.js"></script>
<script src="/assets/libs/feather-icons/feather-icons.min.js"></script>
<script src="/assets/js/pages/plugins/lord-icon-2.1.0.js"></script>

@livewireScripts
<!-- App js -->
<script src="/assets/js/app.js"></script>
<script src="{{mix('js/logic.js')}}"></script>

</body>

</html>
