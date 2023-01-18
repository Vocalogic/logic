@extends('layouts.installer')

@section('content')
    <div class="row">
        <div class="col-lg-5 d-none d-lg-flex justify-content-center align-items-center">


            <!-- List Checked -->

            <ul class="list-unstyled mb-5">
                <li class="mb-4">
                    <span class="d-block mb-1 fs-4 fw-light">Welcome to Logic v{{currentVersion()->version}}</span>
                    <span class="color-600">We need to get some information to finish your initial Logic setup.</span>
                </li>

            </ul>
        </div>


        <div class="col-lg-7 d-flex justify-content-center align-items-center">
            <div class="card shadow-sm w-100 p-4 p-md-5" style="max-width: 32rem;">
                <!-- Form -->
                <form method="post" action="/install" class="row g-3">
                    @method('POST')
                    @csrf
                    <div class="col-12 text-center mb-5">
                        <h1>Get Started!</h1>
                        <span>Let's create your admin account.</span>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Admin's Full Name</label>
                        <input type="text" name="name" required class="form-control form-control-lg" placeholder="John Smith">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="company" required class="form-control form-control-lg" placeholder="My Company">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Email address</label>
                        <input type="email" name="email" required class="form-control form-control-lg" placeholder="name@mycompany.com">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" required class="form-control form-control-lg" placeholder="6+ characters required">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Confirm password</label>
                        <input type="password" name="password2" required class="form-control form-control-lg">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Start Leads, Quotes, Invoices at #</label>
                        <input type="text" name="invoices" value="1053" required class="form-control form-control-lg">
                    </div>

                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-lg btn-block btn-dark lift text-uppercase">Create Account</button>
                    </div>
                </form>
                <!-- End Form -->
            </div>
        </div>


    </div>

@endsection
