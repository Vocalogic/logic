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

                        <table class="table table-striped table-sm datatable">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Access Level</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach(\App\Models\User::where('account_id', 1)->get() as $user)
                                <tr>
                                    <td><a class="live" data-title="Edit {{$user->name}}"
                                           href="/admin/users/{{$user->id}}">{{$user->name}}</a>
                                    @if(!$user->active)
                                        <span class="badge bg-danger">deactivated</span>
                                    @endif
                                    </td>
                                    <td>{{$user->email}}</td>
                                    <td>{{$user->acl->getHuman()}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    @endif
                </div>
            </div>
            <a class="btn btn-primary mt-3" href="#newUser" data-bs-toggle="modal">
                <i class="fa fa-plus"></i> Add Team Member
            </a>
        </div>
    </div>




    <div class="modal fade" id="newUser" tabindex="-1" role="dialog" aria-labelledby="liveModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add new Team Member </h5>
                </div>
                <div class="modal-body">
                    <p class="mb-3">
                        Team members added here will be sent an invite and password reset link to login.
                    </p>
                    <div class="card border-primary">
                        <div class="card-body">

                            <form method="post" action="/admin/users">
                                @method('POST')
                                @csrf
                                <div class="row g-3 mb-3">
                                    <div class="col-lg-12">
                                        <x-form-input name="name" label="Member Name" icon="user">
                                            Enter team member's first and last name.
                                        </x-form-input>
                                        <x-form-input name="email" label="E-mail Address" icon="mail-reply">
                                            Enter member's email address
                                        </x-form-input>
                                        @props(['acl' =>  \App\Enums\Core\ACL::getSelectable()])
                                        <x-form-select name="acl" label="Select Access" icon="check-circle-o"
                                                       :options="$acl">
                                            Select access level
                                        </x-form-select>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 mt-3">
                                    <button type="submit" class="btn btn-primary ladda pull-right" data-style="zoom-out">
                                        <i class="fa fa-save"></i> Add Team Member</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
