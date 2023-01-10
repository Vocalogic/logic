<div class="row">
    <div class="col-8">

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
                    <td><a class="live" data-title="Edit {{$user->name}}" href="/admin/accounts/{{$account->id}}/users/{{$user->id}}">{{$user->name}}</a></td>
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

        <a href="#newUser" data-bs-toggle="modal" class="mt-3 btn btn-{{bm()}}primary"><i class="fa fa-plus"></i> new
            user</a>
    </div>

</div>


<div class="modal fade" id="newUser" tabindex="-1" role="dialog" aria-labelledby="liveModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create new User</h5>
            </div>
            <div class="modal-body">

                <p class="mb-3">
                    There can only be one Account Administrator. If you change the access level of another user to primary admin,
                    then the other user will be demoted to a normal user. There must also be at least one account administrator.
                </p>
                <form method="post" action="/admin/accounts/{{$account->id}}/users">
                    @method('POST')
                    @csrf
                    <div class="row g-3 mb-3">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="name" value="">
                                <label>User's First/Last Name</label>
                                <span class="helper-text">Enter the first and last name</span>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="email" value="">
                                <label>E-mail Address</label>
                                <span class="helper-text">Enter user's email address</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">

                        <div class="col-lg-6 col-md-12">
                            <div class="form-floating">
                                {{Form::select('acl', \App\Enums\Core\ACL::getSelectable(), \App\Enums\Core\ACL::USER->value, ['class' => 'form-control'] )}}
                                <label>Select Access Level</label>
                                <span class="helper-text">Select access level</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">

                        <div class="col-lg-12 col-md-12 mt-3">
                            <input type="submit" class="btn btn-primary rounded wait" data-anchor=".modal" value="Save">
                        </div>
                    </div>
                </form>




            </div>
        </div>
    </div>
</div>
