@extends('layouts.admin', ['title' => $account->name, 'crumbs' => [
    '/admin/accounts' => "Accounts",
    "/admin/accounts/$account->id" => $account->name,
    'Users'

], 'log' => $account->logLink])
@section('content')
    <div class="row">
        <div class="col-lg-2 col-xs-12">
            @include('admin.accounts.submenu')
        </div>

        <div class="col-lg-10 col-xs-12">


            <table class="table table-sm">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Access</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($account->users as $user)
                    <tr class="{{!$user->active ? "bg-light-danger" : null}}">
                        <td><a class="live" data-title="Edit {{$user->name}}"
                               href="/admin/accounts/{{$account->id}}/users/{{$user->id}}">{{$user->name}}</a></td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->acl->getHuman()}}</td>
                        <td>
                            <a href="/admin/accounts/{{$account->id}}/users/{{$user->id}}/shadow"
                               class="btn btn-link btn-sm text-muted"
                               data-bs-toggle="tooltip" data-bs-placement="top" title="Login as {{$user->first}}">
                                <i class="fa fa-arrow-right"></i>
                            </a>

                            <a href="/admin/accounts/{{$account->id}}/users/{{$user->id}}/reset"
                               class="btn btn-link btn-sm text-muted confirm"
                               data-method="GET"
                               data-message="Are you sure you want to send {{$user->name}} a password reset request?"
                               data-bs-toggle="tooltip" data-bs-placement="top" title="Send Password Reset"><i
                                    class="fa fa-refresh"></i>
                            </a>
                            @if($user->acl->value != 'ADMIN' && $user->active)

                                <a href="/admin/accounts/{{$account->id}}/users/{{$user->id}}"
                                   class="btn btn-link btn-sm text-muted confirm"
                                   data-method="DELETE"
                                   data-message="Are you sure you want to deactivate {{$user->name}}. This will not remove the user but prevent them from logging in."
                                   data-bs-toggle="tooltip" data-bs-placement="top" title="Deactivate User"><i
                                        class="fa fa-times"></i>
                                </a>
                            @endif

                            @if($user->acl->value != 'ADMIN' && !$user->active)

                                <a href="/admin/accounts/{{$account->id}}/users/{{$user->id}}"
                                   class="btn btn-link btn-sm text-muted confirm"
                                   data-method="DELETE"
                                   data-message="Are you sure you want to activate {{$user->name}}. This will allow the user to login again./"
                                   data-bs-toggle="tooltip" data-bs-placement="top" title="Activate User"><i
                                        class="fa fa-user-circle"></i>
                                </a>
                            @endif


                        </td>
                    </tr>

                @endforeach
                </tbody>
            </table>

            <a href="#newUser" data-bs-toggle="modal" class="mt-3 btn btn-primary">
                <i class="fa fa-plus"></i> new user</a>
        </div>

    </div>

    <x-modal name="newUser" title="Add User to {{$account->name}}">
        <p class="mb-3">
            There can only be one Account Administrator. If you change the access level of another user to
            primary admin,
            then the other user will be demoted to a normal user. There must also be at least one account
            administrator.
        </p>
        <div class="card border-primary">
            <div class="card-body">
                <form method="post" action="/admin/accounts/{{$account->id}}/users">
                    @method('POST')
                    @csrf

                    <x-form-input name="name" icon="user" label="First and Last Name" placeholder="John Smith">
                        Enter first and last name for the user.
                    </x-form-input>

                    <x-form-input name="email" icon="reply" label="E-mail Address" placeholder="user@email.com">
                        Enter user's email address.
                    </x-form-input>
                    @props(['acl' => \App\Enums\Core\ACL::getSelectable()])
                    <x-form-select name="acl" :options="$acl"
                                   label="Select Access Level"
                                   icon="database"
                                   selected="{{\App\Enums\Core\ACL::USER->value}}">
                        Select access level for user.
                    </x-form-select>
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-primary pull-right ladda" data-style="zoom-out">
                                <i class="fa fa-save"></i> Add User
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </x-modal>

@endsection
