@extends('layouts.admin', ['title' => "Bug Reports", 'crumbs' => [
     "Bug Reports",
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Bug Reports</h1>
            <small class="text-muted">If you found something not working quite right, let us know below!</small>
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
                            boardToken: '27fa10cd-93b7-bfa2-f3c9-f0acef6c698c',
                            basePath: '/admin/bug',
                            ssoToken: '{{user()->sso}}'
                        });
                    </script>
                </div>
            </div>

        </div>
    </div>

@endsection
