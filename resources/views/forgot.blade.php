@extends('layouts.installer', ['title' => "Reset your " . setting('brand.name') . " Password"])

@section('content')
    <div class="row">
        <div class="col-lg-6 d-none d-lg-flex justify-content-center align-items-center">
            <div style="max-width: 25rem;">
                <div class="mb-4">
                    <img width="300" src="{{_file(setting('brandImage.light'))?->relative}}" alt="{{setting('brand.name')}}">

                </div>
                <div class="mb-5">
                    <h4 class="color-900">Reset Your {{setting('brand.name')}} Password</h4>
                </div>
                <!-- List Checked -->
                <ul class="list-unstyled mb-5">
                    <li class="mb-4">
                        <span class="d-block mb-1 fs-4 fw-light">Get Started</span>
                        <span class="color-600">Manage your invoices, orders and services. </span>
                    </li>
                    <li>

                        <span class="color-600">{{setting('shop.info')}}</span>
                    </li>
                </ul>

            </div>
        </div>

        <div class="col-lg-6 d-flex justify-content-center align-items-center">

            <div class="card shadow-sm w-100 p-4 p-md-5" style="max-width: 32rem;">

                @if ($errors->any())
                    @foreach($errors->getMessages() as $error)
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {!! $error[0] !!}
                        </div>
                    @endforeach
                @endif
                @if(session('message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {!! session('message') !!}
                        </div>
                @endif

                <!-- Form -->
                <form method="post" action="/forgot" class="row g-3">
                    @method('POST')
                    @csrf
                    <div class="col-12 text-center mb-5">
                        <h1>Reset Password</h1>
                        <span>Enter your email below to reset your password.</span>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Email address</label>
                        <input type="email" name="email" required class="form-control form-control-lg" placeholder="name@mycompany.com">
                    </div>

                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-lg btn-block btn-dark lift text-uppercase">Send Reset Link</button>
                        <p class="mt-3">
                            <a class="text-{{currentMode() == 'dark' ? "white" : "primary"}}" href="/login">I remember my password</a>
                        </p>
                    </div>
                </form>
                <!-- End Form -->
            </div>
        </div>


    </div>

@endsection
