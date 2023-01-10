<div class="card">
    <div class="card-body">
        <p class="card-title">Lead Statuses</p>
        <p>
            Define your lead statuses here. This can be used for automation depending on which status is set.
        </p>
        <table class="table">
            <thead>
            <tr>
                <th>Status</th>
                <th>Type</th>
            </tr>
            </thead>
            <tbody>
            @foreach(\App\Models\LeadStatus::all() as $status)
                <tr>
                    <td>
                        @if($status->locked)
                            {{$status->name}} <span class="badge bg-danger pull-right">locked</span>
                        @else
                            <a class="live" data-title="Edit {{$status->name}}"
                               href="/admin/lead_statuses/{{$status->id}}">{{$status->name}}</a>
                        @endif
                    </td>
                    <td>
                        @if($status->is_won)
                            Won
                        @elseif($status->is_lost)
                            Lost
                        @else
                            In Progress
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <a class="btn btn-{{bm()}}primary" data-bs-toggle="modal" href="#newStatus"><i class="fa fa-plus"></i> Add
            Status</a>
    </div>
</div>

<div class="modal fade" id="newStatus" tabindex="-1" role="dialog" aria-labelledby="liveModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create new Lead Status</h5>
            </div>
            <div class="modal-body">
                <form method="post" action="/admin/lead_statuses">
                    @method('POST')
                    @csrf
                    <p>
                        Enter a new stage and where in the lifecycle the lead is given assigned this new stage.
                    </p>
                    <div class="row g-3 mb-4">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="name" value="">
                                <label>Lead Stage</label>
                                <span class="helper-text">Enter the Lead Stage</span>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-12 mt-3">
                            <div class="form-floating">
                                {!! Form::select('lead_type', ['progress' => "In Progress", 'won' => "Won Lead", 'lost' => "Lost Lead"], null, ['class' => "form-control"]) !!}
                                <label>Select Type</label>
                                <span class="helper-text">Select the type of Lead Stage</span>
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
            </div>
        </div>
    </div>
</div>
