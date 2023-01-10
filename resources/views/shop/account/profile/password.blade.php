@extends('layouts.shop.main', ['title' => "Change Password", 'crumbs' => [
     "/shop" => "Home",
     "/shop/account" => "My Account",
     "Change Password"
]])

@section('content')

    <section class="log-in-section background-image-2 section-b-space">
        <div class="container-fluid-lg w-100">
            <div class="row">

                <div class="col-xxl-4 col-xl-5 col-lg-6 me-auto">
                    <div class="log-in-box">
                        <div class="log-in-title">
                            <h3>Change Password</h3>
                            <h4>Enter a new password for your account</h4>
                        </div>

                        <div class="input-box">
                            <form class="row g-4" method="POST" action="/shop/account/password">
                                @csrf
                                @method('POST')
                                <div class="col-12">
                                    <div class="form-floating theme-form-floating log-in-form">
                                        <input type="password" name="password" class="form-control" id="password"
                                               placeholder="Password">
                                        <label for="password">Password</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating theme-form-floating log-in-form">
                                        <input type="password" name="password2" class="form-control" id="password2"
                                               placeholder="Confirm Password">
                                        <label for="password2">Confirm Password</label>
                                    </div>
                                </div>


                                <div class="col-12">
                                    <button class="btn btn-animation w-100 justify-content-center" type="submit">Update
                                        Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
