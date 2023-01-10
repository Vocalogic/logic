@extends('layouts.admin', ['title' => 'My Team'])


@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Manage Team</h1>
            <small class="text-muted">Add your team members to Logic for Sales/Logistics Tracking</small>
        </div>

    </div> <!-- .row end -->

@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    @if(env('DEMO_MODE'))
                        <div class="alert alert-primary">Team management is disabled in demo mode.</div>
                    @else

                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <td>Name</td>
                                <td>Email</td>
                                <td>Access Level</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach(\App\Models\User::where('account_id', 1)->get() as $user)
                                <tr>
                                    <td><a class="live" data-title="Edit {{$user->name}}"
                                           href="/admin/users/{{$user->id}}">{{$user->name}}</a></td>
                                    <td>{{$user->email}}</td>
                                    <td>{{$user->acl->getHuman()}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <a class="btn btn-primary mt-3" href="#newUser" data-bs-toggle="modal"><i
                                class="fa fa-plus"></i>
                            Add Team Member</a>
                    @endif
                </div>

            </div>
        </div>
    </div>




    <div class="modal fade" id="newUser" tabindex="-1" role="dialog" aria-labelledby="liveModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create new Vendor</h5>
                </div>
                <div class="modal-body">
                    <p class="mb-3">
                        Team members added here will be sent an invite and password reset link to login.
                    </p>
                    <form method="post" action="/admin/users">
                        @method('POST')
                        @csrf
                        <div class="row g-3 mb-3">
                            <div class="col-lg-6 col-md-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="name" value="">
                                    <label>Member Name</label>
                                    <span class="helper-text">Enter the first and last name</span>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="email" value="">
                                    <label>E-mail Address</label>
                                    <span class="helper-text">Enter member's email address</span>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="col-lg-5 col-md-12">
                                    <div class="form-floating">
                                        {!! Form::select('acl', \App\Enums\Core\ACL::getSelectable(), null, ['class' => 'form-control']) !!}
                                        <label>Select Access</label>
                                        <span class="helper-text">Select Access Level</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 mt-3">
                            <input type="submit" class="btn btn-primary rounded wait" data-anchor=".modal" value="Save">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
