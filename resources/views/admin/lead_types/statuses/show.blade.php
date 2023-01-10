<form method="post" action="/admin/lead_statuses/{{$status->id}}">
    @method('PUT')
    @csrf
    <p>
        Enter a new stage and where in the lifecycle the lead is given assigned this new stage.
    </p>
    <div class="row g-3 mb-4">
        <div class="col-lg-6 col-md-12">
            <div class="form-floating">
                <input type="text" class="form-control" name="name" value="{{$status->name}}">
                <label>Lead Stage</label>
                <span class="helper-text">Enter the Lead Stage</span>
            </div>
        </div>

        <div class="col-lg-6 col-md-12 mt-3">
            <div class="form-floating">
                {!! Form::select('lead_type', ['progress' => "In Progress", 'won' => "Won Lead", 'lost' => "Lost Lead"], $status->getSelected(), ['class' => "form-control"]) !!}
                <label>Select Type</label>
                <span class="helper-text">Select the type of lead stage.</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mt-3">
            <div class="form-floating">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" {{$status->disable_warnings ? "checked" : null}} role="switch" value="1" id="warnings"
                           name="disable_warnings">
                    <label class="form-check-label" for="warnings">Disable Stale Warnings for this Stage?</label>
                </div>
            </div>
        </div>


        <div class="col-lg-12 col-md-12 mt-3">
            <input type="submit" class="btn btn-primary rounded" value="Save">
            <a class="btn btn-danger confirm pull-right" data-message="Are you sure you want to remove this lead stage?"
               data-method="DELETE" href="/admin/lead_statuses/{{$status->id}}"><i class="fa fa-trash"></i> Delete Lead
                Stage</a>
        </div>
    </div>
</form>
