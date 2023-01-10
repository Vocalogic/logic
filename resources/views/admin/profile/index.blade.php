@extends('layouts.admin', ['title' => 'My Profile'])


@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Edit Profile</h1>
            <small class="text-muted">Edit your profile and sales goals</small>
        </div>

    </div> <!-- .row end -->

@endsection

@section('content')
    <div class="offset-2 col-7 mt-3 mb-5">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">My Profile</h5>
                <p>
                    Change your password or update your personal sales goals.
                </p>
                <form method="POST" action="/admin/profile" class="mt-3">
                    @method('POST')
                    @csrf

                    <div class="row mb-3">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="name" value="{{user()->name}}">
                                <label>Your Name</label>
                                <span class="helper-text">Update your name</span>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="email" disabled value="{{user()->email}}">
                                <label>Your Email</label>
                                <span class="helper-text">Email changes must be done via an admin</span>
                            </div>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-floating">
                                <input type="password" class="form-control" name="password">
                                <label>New Password</label>
                                <span class="helper-text">To change your password enter a new one here.</span>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-12">
                            <div class="form-floating">
                                <input type="password" class="form-control" name="password2">
                                <label>New Password (confirm)</label>
                                <span class="helper-text">Confirm your new password here.</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="phone" value="{{user()->phone}}">
                                <label>2FA Mobile Number</label>
                                <span class="helper-text">If SMS Two-Factor authentication, enter your mobile number.</span>
                            </div>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="goal_self_monthly" value="{{user()->goal_self_monthly}}">
                                <label>Monthly MRR Goal</label>
                                <span class="helper-text">Enter your monthly MRR goal (sold)</span>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="goal_self_quarterly" value="{{user()->goal_self_quarterly}}">
                                <label>Quarterly MRR Goal</label>
                                <span class="helper-text">By default will be Monthly * 3</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-12 col-md-12 mt-3">
                            <input type="submit" class="btn btn-primary rounded wait" value="Save">
                        </div>
                    </div>





                </form>


                </form>
            </div>
        </div>
    </div>

@endsection
