<p class="mb-3">
    Team members added here will be sent an invite and password reset link to login.
</p>
<form method="post" action="/admin/users/{{$u->id}}" class="formAnchor">
    @method('PUT')
    @csrf
    <div class="row g-3 mb-3">
        <div class="col-lg-6 col-md-12">
            <div class="form-floating">
                <input type="text" class="form-control" name="name" value="{{$u->name}}">
                <label>Member Name</label>
                <span class="helper-text">Enter the first and last name</span>
            </div>
        </div>

        <div class="col-lg-6 col-md-12">
            <div class="form-floating">
                <input type="text" class="form-control" name="email" value="{{$u->email}}">
                <label>E-mail Address</label>
                <span class="helper-text">Enter member's email address</span>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <div class="row">
            <div class="col-lg-3">
                <input type="color" name="color" class="form-control form-control-color" value="{{$u->color}}"
                       title="Select color">
                <label>Calendar Color</label>
                <span class="helper-text">Select a color for the event calendar</span>

            </div>
            <div class="col-lg-4 col-md-12">
                <div class="form-floating">
                    <input type="text" class="form-control" name="phone" value="{{$u->phone}}">
                    <label>Mobile Phone</label>
                    <span class="helper-text">Enter member's mobile phone for 2FA</span>
                </div>
            </div>
            <div class="col-lg-5 col-md-12">
                <div class="form-floating">
                    {!! Form::select('acl', \App\Enums\Core\ACL::getSelectable(), $u->acl->value, ['class' => 'form-control']) !!}
                    <label>Select Access</label>
                    <span class="helper-text">Select Access Level</span>
                </div>
            </div>

        </div>

        <div class="row mt-3">
            <div class="col-xxl-12 col-md-12">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" {{$u->requires_approval ? "checked" : null}} role="switch" value="1" id="approval"
                           name="requires_approval">
                    <label class="form-check-label" for="approval">Require Approval for Quotes</label>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-6 col-md-12">
                <div class="form-floating">
                    <input type="text" class="form-control" name="goal_monthly" value="{{$u->goal_monthly}}">
                    <label>Company Monthly MRR Goal</label>
                    <span class="helper-text">Enter monthly MRR goal (sold)</span>
                </div>
            </div>

            <div class="col-lg-6 col-md-12">
                <div class="form-floating">
                    <input type="text" class="form-control" name="goal_quarterly" value="{{$u->goal_quarterly}}">
                    <label>Company Quarterly MRR Goal</label>
                    <span class="helper-text">Enter company issued monthly MRR goal (sold)</span>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-6 col-md-12">
                <div class="form-floating">
                    <input type="text" class="form-control" name="goal_f_monthly" value="{{$u->goal_f_monthly}}">
                    <label>Company Monthly MRR Forecasted Goal</label>
                    <span class="helper-text">Enter company issued monthly forecasted MRR goal</span>
                </div>
            </div>

            <div class="col-lg-6 col-md-12">
                <div class="form-floating">
                    <input type="text" class="form-control" name="goal_f_quarterly" value="{{$u->goal_f_quarterly}}">
                    <label>Company Quarterly MRR Forecasted Goal</label>
                    <span class="helper-text">Enter company issued quarterly MRR goal</span>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-6 col-md-12">
                <div class="form-floating">
                    <input type="text" class="form-control" name="agent_comm_mrc" value="{{$u->agent_comm_mrc}}">
                    <label>Agent Commission Percentage (MRC)</label>
                    <span class="helper-text">Enter the percentage of MRR this user gets as commission</span>
                </div>
            </div>

            <div class="col-lg-6 col-md-12">
                <div class="form-floating">
                    <input type="text" class="form-control" name="agent_comm_spiff" value="{{$u->agent_comm_spiff}}">
                    <label>Agent Commission Spiff (In Months MRR)</label>
                    <span class="helper-text">Enter number of months user will get in commission. (MRR x months)</span>
                </div>
            </div>
        </div>



        <div class="col-lg-12 col-md-12 mt-3">
            <input type="submit" class="btn btn-primary rounded wait" data-anchor=".formAnchor" value="Save">

            <a class="confirm mt-3 btn btn-light-primary pull-right" data-method="GET"
               data-message="Are you sure you want to send a password reset request?"
               href="/admin/users/{{$u->id}}/reset">Send Password Reset Link</a>
        </div>


</form>
