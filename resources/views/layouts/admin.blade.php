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
        <link rel="icon" href="{{_file(setting('brandImage.icon'))?->relative}}" type="image/png"> <!-- Favicon-->
    @endif
    <!-- plugin css file  -->

    <!-- project css file  -->

    <link rel="stylesheet" href="/assets/css/dataTables.min.css">
    <link rel="stylesheet" href="/assets/css/select2.min.css">
    <link rel="stylesheet" href="/assets/css/dropify.min.css">
    <link rel="stylesheet" href="/assets/plugin/rating/rating.css"/>
    <link rel="stylesheet" href="/assets/js/wm/waitMe.css">
    <link rel="stylesheet" href="/assets/css/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/css/fancybox.min.css">
    <link rel="stylesheet" href="/assets/css/jquerysteps.min.css">
    <link rel="stylesheet" href="/assets/css/dataTables.min.css">
    <link rel="stylesheet" href="/assets/css/fullcalendar.min.css">


    <!-- project css file  -->
    <link rel="stylesheet" href="/assets/css/luno.style.css">
    <link rel="stylesheet" href="/assets/css/logic.css">
    <link rel="stylesheet" href="/assets/css/bootstrapdatepicker.min.css">
    <link rel="stylesheet" href="{{mix('/css/all.css')}}">


    <style>
        .popover {
            background-color: {{currentMode() == 'dark' ? 'var(--dark-color)' : 'var(--white-color)'}};
            color: var(--primary-color);
        }
    </style>

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
        @include('admin.partials.core.admin_menu')
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
                        @livewire('admin.search-component')
                    </div>
                    <!-- start: link -->
                    <ul class="header-right justify-content-end d-flex align-items-center mb-0">
                        <!-- start: notifications dropdown-menu -->

                        <!-- start: Language dropdown-menu -->

                        <!-- start: Grid app dropdown-menu -->
                        <li class="d-none d-lg-inline-block">
                            <div class="dropdown morphing scale-left grid-menu mx-sm-2">
                                <a class="nav-link dropdown-toggle after-none" href="#" role="button"
                                   data-bs-toggle="dropdown">
                                    <svg viewBox="0 0 16 16" width="18px" fill="currentColor"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2 10H5C5.26522 10 5.51957 10.1054 5.70711 10.2929C5.89464 10.4804 6 10.7348 6 11V14C6 14.2652 5.89464 14.5196 5.70711 14.7071C5.51957 14.8946 5.26522 15 5 15H2C1.73478 15 1.48043 14.8946 1.29289 14.7071C1.10536 14.5196 1 14.2652 1 14V11C1 10.7348 1.10536 10.4804 1.29289 10.2929C1.48043 10.1054 1.73478 10 2 10ZM11 1H14C14.2652 1 14.5196 1.10536 14.7071 1.29289C14.8946 1.48043 15 1.73478 15 2V5C15 5.26522 14.8946 5.51957 14.7071 5.70711C14.5196 5.89464 14.2652 6 14 6H11C10.7348 6 10.4804 5.89464 10.2929 5.70711C10.1054 5.51957 10 5.26522 10 5V2C10 1.73478 10.1054 1.48043 10.2929 1.29289C10.4804 1.10536 10.7348 1 11 1ZM11 10C10.7348 10 10.4804 10.1054 10.2929 10.2929C10.1054 10.4804 10 10.7348 10 11V14C10 14.2652 10.1054 14.5196 10.2929 14.7071C10.4804 14.8946 10.7348 15 11 15H14C14.2652 15 14.5196 14.8946 14.7071 14.7071C14.8946 14.5196 15 14.2652 15 14V11C15 10.7348 14.8946 10.4804 14.7071 10.2929C14.5196 10.1054 14.2652 10 14 10H11ZM11 0C10.4696 0 9.96086 0.210714 9.58579 0.585786C9.21071 0.960859 9 1.46957 9 2V5C9 5.53043 9.21071 6.03914 9.58579 6.41421C9.96086 6.78929 10.4696 7 11 7H14C14.5304 7 15.0391 6.78929 15.4142 6.41421C15.7893 6.03914 16 5.53043 16 5V2C16 1.46957 15.7893 0.960859 15.4142 0.585786C15.0391 0.210714 14.5304 0 14 0L11 0ZM2 9C1.46957 9 0.960859 9.21071 0.585786 9.58579C0.210714 9.96086 0 10.4696 0 11L0 14C0 14.5304 0.210714 15.0391 0.585786 15.4142C0.960859 15.7893 1.46957 16 2 16H5C5.53043 16 6.03914 15.7893 6.41421 15.4142C6.78929 15.0391 7 14.5304 7 14V11C7 10.4696 6.78929 9.96086 6.41421 9.58579C6.03914 9.21071 5.53043 9 5 9H2ZM9 11C9 10.4696 9.21071 9.96086 9.58579 9.58579C9.96086 9.21071 10.4696 9 11 9H14C14.5304 9 15.0391 9.21071 15.4142 9.58579C15.7893 9.96086 16 10.4696 16 11V14C16 14.5304 15.7893 15.0391 15.4142 15.4142C15.0391 15.7893 14.5304 16 14 16H11C10.4696 16 9.96086 15.7893 9.58579 15.4142C9.21071 15.0391 9 14.5304 9 14V11Z"/>
                                        <path class="fill-secondary"
                                              d="M0.585786 0.585786C0.210714 0.960859 0 1.46957 0 2V5C0 5.53043 0.210714 6.03914 0.585786 6.41421C0.960859 6.78929 1.46957 7 2 7H5C5.53043 7 6.03914 6.78929 6.41421 6.41421C6.78929 6.03914 7 5.53043 7 5V2C7 1.46957 6.78929 0.960859 6.41421 0.585786C6.03914 0.210714 5.53043 0 5 0H2C1.46957 0 0.960859 0.210714 0.585786 0.585786Z"/>
                                    </svg>
                                </a>
                                <div class="dropdown-menu rounded-4 shadow border-0 p-0" data-bs-popper="none">
                                    @include('admin.partials.core.quad_links')
                                </div>
                            </div>
                        </li>
                        <!-- start: My notes toggle modal -->

                        <!-- start: quick light dark -->
                        <li>
                            <a class="nav-link quick-light-dark" href="/mode/toggle" data-bs-placement="left" data-bs-toggle="tooltip" title="Switch to {{currentMode() == 'light' ? "dark" : "light"}} mode">
                                <svg viewBox="0 0 16 16" width="18px" fill="currentColor"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278zM4.858 1.311A7.269 7.269 0 0 0 1.025 7.71c0 4.02 3.279 7.276 7.319 7.276a7.316 7.316 0 0 0 5.205-2.162c-.337.042-.68.063-1.029.063-4.61 0-8.343-3.714-8.343-8.29 0-1.167.242-2.278.681-3.286z"/>
                                    <path class="fill-secondary"
                                          d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z"/>
                                </svg>
                            </a>
                        </li>
                        @if(isset($docs))
                            <li>
                                <a class="nav-link quick-light-dark" target="_blank" data-bs-placement="left" href="{{$docs}}" data-bs-toggle="tooltip" title="{{$title}} Documentation">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" fill="currentColor" viewBox="0 0 16 16">
                                        <path class="fill-secondary" d="M5 10.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5zm0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"></path>
                                        <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2z"></path>
                                        <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1z"></path>
                                    </svg>
                                </a>
                            </li>
                        @endif

                        @if(isset($video))
                            <li>
                                <a class="nav-link quick-light-dark" target="_blank" href="{{$video}}" data-bs-toggle="tooltip" data-bs-placement="left" title="Video Tutorial">
                                    <svg width="18" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0 3H16V4H0V3Z"></path>
                                        <path d="M9 1H14V6H9V1Z"></path>
                                        <path d="M0 13H16V14H0V13Z"></path>
                                        <path d="M9 11H14V16H9V11Z"></path>
                                        <path class="fill-secondary" d="M0 8H16V9H0V8Z"></path>
                                        <path class="fill-secondary" d="M2 6H7V11H2V6Z"></path>
                                    </svg>
                                </a>
                            </li>
                        @endif

                        <!-- start: model's logs -->
                        @if(isset($log))
                            <li>
                                <a class="nav-link quick-light-dark live" data-size="modal-xl" href="/admin/logs/{{$log['type']}}/{{$log['id']}}" data-bs-toggle="tooltip" data-bs-placement="left" title="Logs" data-title="Logs">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" fill="currentColor" viewBox="0 0 16 16">
                                        <path class="fill-secondary" d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"></path>
                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"></path>
                                    </svg>
                                </a>
                            </li>
                        @endif

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
                                               href="/admin/profile"><i class="w30 fa fa-user"></i>My Profile</a>

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
        <script>!function (w, d, i, s) {
                function l() {
                    if (!d.getElementById(i)) {
                        var f = d.getElementsByTagName(s)[0], e = d.createElement(s);
                        e.type = "text/javascript", e.async = !0, e.src = "https://canny.io/sdk.js", f.parentNode.insertBefore(e, f)
                    }
                }

                if ("function" != typeof w.Canny) {
                    var c = function () {
                        c.q.push(arguments)
                    };
                    c.q = [], w.Canny = c, "complete" === d.readyState ? l() : w.attachEvent ? w.attachEvent("onload", l) : w.addEventListener("load", l, !1)
                }
            }(window, document, "canny-jssdk", "script");</script>

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
                                         class="alert {{currentMode() == 'dark' ? 'bg-light-danger' : 'alert-danger'}}">
                                        {!!  session('error') !!}
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
                                        <div class="toasted" data-message="{{session('message')}}"
                                             data-title="Operation Successful" data-icon="success"></div>
                                    @endif

                                    @if (session()->has('error'))
                                        <div role="alert"
                                             class="alert {{currentMode() == 'dark' ? 'bg-light-danger' : 'alert-danger'}}">
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
    </div>
</div>


<!-- Jquery Core Js -->
<script src="/assets/js/plugins.js"></script>

<!-- Plugin Js -->
<script src="/assets/bundles/dataTables.bundle.js"></script>
<script src="/assets/bundles/select2.bundle.js"></script>
<script src="/assets/bundles/bootstraptagsinput.bundle.js"></script>
<script src="/assets/bundles/bootstrapdatepicker.bundle.js"></script>
<script src="/assets/bundles/dropify.bundle.js"></script>
<script src="/assets/bundles/rating.bundle.js"></script>
<script src="/assets/js/wm/waitMe.min.js"></script>
<script src="/assets/bundles/daterangepicker.bundle.js"></script>
<script src="/assets/bundles/fancybox.bundle.js"></script>
<script src="/assets/bundles/jquerysteps.bundle.js"></script>
<script src="/assets/bundles/dataTables.bundle.js"></script>
<script src="/assets/bundles/fullcalendar.bundle.js"></script>
<script src="/assets/js/tinymce/tinymce.min.js"></script>




    @livewireScripts
<script type="text/javascript" src="{{mix('/js/app.js')}}"></script>
@if(setting('account.maps_key'))
    <script>function handlePlace(place){}</script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key={{setting('account.maps_key')}}&callback=handlePlace"></script>
@endif
@yield('javascript')

</body>
</html>
