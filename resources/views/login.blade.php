@extends('layouts.installer', ['title' => "Login to " . setting('brand.name')])

@section('content')
    <div class="row">
        <div class="col-lg-6 d-none d-lg-flex justify-content-center align-items-center">
            <div style="max-width: 25rem;">
                <div class="mb-4">
                    @if(currentMode() == 'light' && setting('brandImage.light'))
                    <img width="300" src="{{_file(setting('brandImage.light'))?->relative}}" alt="{{setting('brand.name')}}">
                    @else
                        @if(setting('brandImage.dark'))
                        <img width="300" src="{{_file(setting('brandImage.dark'))?->relative}}" alt="{{setting('brand.name')}}">
                        @endif
                    @endif
                </div>
                <div class="mb-5">
                    <h4 class="color-900">Login to {{setting('brand.name')}}</h4>
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
                <!-- Form -->
                <form method="post" action="/login" class="row g-3">
                    @method('POST')
                    @csrf
                    <div class="col-12 text-center mb-5">
                        <h1>Login</h1>
                        <span>Login to your account below.</span>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Email address</label>
                        <input type="email" name="email" required class="form-control form-control-lg" placeholder="name@mycompany.com"
                        @if(env('DEMO_MODE'))value="admin@demo.com" @endif>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" required class="form-control form-control-lg"
                               @if(env('DEMO_MODE'))value="demo" @endif>
                    </div>

                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-lg btn-block btn-dark lift text-uppercase">Login</button>
                        <p class="mt-3">
                            <a class="text-{{currentMode() == 'dark' ? "white" : "primary"}}" href="/forgot">Forgot Password?</a>
                        </p>
                    </div>
                </form>
                <!-- End Form -->
            </div>
        </div>


    </div>

@endsection
