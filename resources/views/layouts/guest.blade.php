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
    <link rel="icon" href="{{_file(setting('brandImage.icon'))?->relative}}" type="image/png"> <!-- Favicon-->

    <!-- plugin css file  -->

    <!-- project css file  -->
    <link rel="stylesheet" href="/assets/css/luno.style.min.css">

    <!-- project css file  -->
    <link rel="stylesheet" href="/assets/css/onepage.kit.min.css">
    <link rel="stylesheet" href="/assets/css/x-editable.min.css" />
    <link rel="stylesheet" href="/assets/js/wm/waitMe.css">
    <link rel="stylesheet" href="/assets/css/dropify.min.css">
    <link rel="stylesheet" href="/assets/js/jquery.signaturepad.css">
    <link rel="stylesheet" href="/assets/css/x-editable.min.css" />
    <link rel="stylesheet" href="/assets/css/fancybox.min.css">
    <link rel="stylesheet" href="/assets/css/logic.css">


</head>

<body class="landing-page" data-luno="theme-blue">

<!-- Page Wrapper Start -->
<div class="wrapper">
    <div class="col-lg-12">
        @if($errors->any())
            <div role="alert" class="alert alert-danger">
                @foreach($errors->all() as $error)
                    {{$error}} <br>
                @endforeach
            </div>
        @endif

        @if (session()->has('message'))
            <div role="alert" class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div role="alert" class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
    </div>
        @yield('content')
</div>

<script src="/assets/js/plugins.js"></script>
<script src="/assets/bundles/x-editable.bundle.js"></script>
<script src="/assets/js/wm/waitMe.min.js"></script>
<script src="/assets/bundles/dropify.bundle.js"></script>
<script src="/assets/bundles/x-editable.bundle.js"></script>
<script src="/assets/bundles/fancybox.bundle.js"></script>

<script src="/assets/js/jquery.signaturepad.min.js"></script>

@livewireScripts

<script type="text/javascript" src="{{mix('/js/app.js')}}"></script>

@yield('javascript')
</body>
</html>
