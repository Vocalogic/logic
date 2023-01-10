<form method="post" action="/admin/leads/{{$lead->id}}/status">
    @method('PUT')
    @csrf
    <p>
        Select the stage for this lead. Note, if there are any automations tied to the status that you are setting
        they will be triggered upon save.
    </p>
    <div class="row g-3 mb-4">
        <div class="col-lg-12 col-md-12">
            <div class="form-floating">
                {!! Form::select('lead_status_id', \App\Models\LeadStatus::getSelectable(), $lead->lead_status_id, ['class' => 'form-control']) !!}
                <label>Lead Stage</label>
                <span class="helper-text">Select the Lead Stage</span>
            </div>
        </div>


    </div>
    <div class="col-lg-12 col-md-12 mt-3">
        <input type="submit" class="btn btn-primary rounded" value="Set Stage">
    </div>
</form>
