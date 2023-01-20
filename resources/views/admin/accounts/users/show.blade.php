<p class="mb-3">
    There can only be one Account Administrator. If you change the access level of another user to primary admin,
    then the other user will be demoted to a normal user. There must also be at least one account administrator.
</p>
<form method="post" action="/admin/accounts/{{$account->id}}/users/{{$u->id}}" class="userForm">
    @method('PUT')
    @csrf
    <div class="card border-primary">
        <div class="card-body">
            <x-form-input name="name" icon="user" label="First and Last Name" placeholder="John Smith"
                          value="{{$u->name}}">
                Enter first and last name for the user.
            </x-form-input>

            <x-form-input name="email" icon="reply" label="E-mail Address" placeholder="user@email.com"
                          value="{{$u->email}}">
                Enter user's email address.
            </x-form-input>
            @props(['acl' => \App\Enums\Core\ACL::getSelectable()])
            <x-form-select name="acl" :options="$acl"
                           label="Select Access Level"
                           icon="database"
                           selected="{{$u->acl->value}}">
                Select access level for user.
            </x-form-select>
            <div class="row mb-3">
                <div class="col-lg-12 col-md-12 mt-3">
                    <input type="submit" class="btn btn-primary rounded wait" data-anchor=".userForm"
                           value="Save">
                </div>
            </div>
        </div>
    </div>
</form>
