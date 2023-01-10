<p class="mb-3">
    There can only be one Account Administrator. If you change the access level of another user to primary admin,
    then the other user will be demoted to a normal user. There must also be at least one account administrator.
</p>
<form method="post" action="/admin/accounts/{{$account->id}}/users/{{$u->id}}">
    @method('PUT')
    @csrf
    <div class="row g-3 mb-3">
        <div class="col-lg-6 col-md-12">
            <div class="form-floating">
                <input type="text" class="form-control" name="name" value="{{$u->name}}">
                <label>User's First/Last Name</label>
                <span class="helper-text">Enter the first and last name</span>
            </div>
        </div>

        <div class="col-lg-6 col-md-12">
            <div class="form-floating">
                <input type="text" class="form-control" name="email" value="{{$u->email}}">
                <label>E-mail Address</label>
                <span class="helper-text">Enter user's email address</span>
            </div>
        </div>
    </div>
    <div class="mb-3">

        <div class="col-lg-12 col-md-12 mt-3">
            <input type="submit" class="btn btn-primary rounded wait" data-anchor=".modal" value="Save">
        </div>
    </div>
</form>
