<div class="card mb-3">
    <div class="card-body border-bottom">
        <div class="d-flex align-items-md-start align-items-center flex-column flex-md-row">
            @if(app('request')->edit || !$lead->logo_id)
                <form method="POST" action="/admin/leads/{{$lead->id}}/logo" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <input type="file" name="logo" class="drop"
                           data-default-file="{{$lead->logo_id ? _file($lead->logo_id)->relative : null}}"/>
                    <button type="submit" name="submit" class="btn btn-sm btn-primary ladda mt-3"
                            data-style="zoom-out">
                        <i class="fa fa-image"></i> Save Logo
                    </button>

                </form>
            @else
                <img height=100 src="{{$lead->logo_id ? _file($lead->logo_id)->relative : null}} " alt=""
                     class="rounded-4">
            @endif
            <div class="media-body ms-md-5 m-0 mt-4 mt-md-0 text-md-start text-center">
                <h4 class="mt-4 mt-lg-0"><strong>{{$lead->company}}</strong></h4>
                <p>
                    {{$lead->contact}} @if($lead->phone)
                        ({{$lead->phone}})
                    @endif
                </p>
                <span class="text-muted">{!! nl2br($lead->description) !!}</span>
                @if($lead->active)
                <a class="btn btn-{{bm()}}danger" data-bs-toggle="modal" href="#suspend"><i class="fa fa-close"></i>
                    Close/Suspend</a>
                @endif
                @if(!$lead->active && $lead->status->is_lost)
                    <a class="btn btn-{{bm()}}info confirm"
                       data-method="GET"
                       href="/admin/leads/{{$lead->id}}/reopen"
                       data-message="Are you sure you want to reactivate this lead?"><i class="fa fa-openid"></i>
                        Reactivate Lead</a>
                @endif
                <div
                    class="d-flex flex-row flex-wrap align-items-center justify-content-center justify-content-md-start mt-3">
                    @if($lead->forecast_date)

                        <div class="card py-2 px-3 me-2 mt-2">
                            <small class="text-muted">Forecasted Conversion</small>
                            <div class="fs-8">{{$lead->forecast_date->format("M d, Y")}}</div>
                        </div>

                    @endif


                    @if(\App\Models\Partner::count() > 0)

                        <div class="card py-2 px-3 me-2 mt-2">
                            <small class="text-muted">Partner</small>
                            <div class="fs-8">
                                <a class="live" data-title="Assign {{$lead->company}} to Partner"
                                   href="/admin/leads/{{$lead->id}}/partner">{{$lead->partner ? $lead->partner->name : "Internal"}}</a>
                            </div>
                        </div>
                    @endif
                    <div class="card py-2 px-3 me-2 mt-2">
                        <small class="text-muted">Lead Agency/Owner</small>
                        <div class="fs-8">
                            {{$lead->agent?->name}} / {{$lead->agent?->account->name}}
                        </div>
                    </div>

                    <div class="card py-2 px-3 me-2 mt-2">
                        <small class="text-muted">Lead Status</small>
                        <div class="fs-8"><a class="live" data-title="Update Lead Status"
                                             href="/admin/leads/{{$lead->id}}/status">{{$lead->status?->name}}</a></div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <ul class="nav nav-tabs tab-card border-bottom-0 pt-2 fs-6 justify-content-center justify-content-md-start"
        role="tablist">
        <li class="nav-item">
            <a class="nav-link" href="/admin/leads/{{$lead->id}}" role="tab">
                <i class="fa fa-refresh"></i><span class="d-none d-sm-inline-block ms-2">Overview</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/leads/{{$lead->id}}/quotes" role="tab">
                <i class="fa fa-money"></i><span class="d-none d-sm-inline-block ms-2">Quotes
                @if($lead->quotes()->where('archived', false)->count())
                        ({{$lead->quotes->where('archived', false)->count()}})
                    @endif</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/admin/leads/{{$lead->id}}?tab=events" role="tab">
                <i class="fa fa-calendar"></i> <span class="d-none d-md-inline-block ms-2">Calendar</span>
            </a>
        </li>

        {!! moduleHook('admin.leads.tabs', ['lead' => $lead]) !!}


    </ul>
</div>

<div class="modal fade" id="suspend" tabindex="-1" role="dialog" aria-labelledby="liveModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Suspend or Close Lead</h5>
            </div>
            <div class="modal-body">
                <form method="post" action="/admin/leads/{{$lead->id}}/close">
                    @method('POST')
                    @csrf
                    <h6 class="fw-bold">Close Details</h6>
                    <p>
                        Select either a permanent lost/close or enter a date to have this lead automatically
                        reactivated.
                        An example of a suspended lead would be a customer who is interested in a later date.
                    </p>
                    <div class="row g-3 mb-4">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="reason" value="">
                                <label>Reason for Close</label>
                                <span class="helper-text">Enter a reason for closing/suspending</span>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-12">
                            <div class="form-floating">
                                <input type="date" class="form-control" name="reactivate_on" value="">
                                <label>Auto-Reactivation Date</label>
                                <span class="helper-text">If suspending, enter the date to reactivate.</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-floating">
                                {!! Form::select('lead_status_id', \App\Models\LeadStatus::getSelectable(true), null, ['class' => 'form-control']) !!}
                                <label>Select Lost Status</label>
                                <span
                                    class="helper-text">Since you are closing this lead what type of lost lead is it?</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 mt-3">
                        <input type="submit" class="btn btn-{{bm()}}primary rounded wait" value="Save">
                    </div>


                </form>
            </div>
        </div>
    </div>
</div>
