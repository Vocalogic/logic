@extends('layouts.admin', ['title' => "Feedback and Feature Requests", 'crumbs' => [
     "Feature Requests",
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Feature Requests and Feedback</h1>
            <small class="text-muted">Help us improve Logic by requesting and voting on new features</small>
        </div>

    </div> <!-- .row end -->

@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card bg-white">
                <div class="card-body">

            <div data-canny />
            <script>
                Canny('render', {
                    boardToken: '3487c753-6fea-9f54-3861-1dcac81cdfd8',
                    basePath: '/admin/feedback',
                    ssoToken: '{{user()->sso}}',
                });
            </script>
                </div>
            </div>

        </div>
    </div>

@endsection
