<div class="card mb-3">
    <div class="card-body">
        <h6 class="mb-3">Discovery</h6>
        <form method="POST" action="/admin/leads/{{$lead->id}}/discovery">
            @csrf
            @method('POST')
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-floating">
                        <textarea name="discovery" class="form-control"
                                  style="height: 100px;">{!! $lead->discovery !!}</textarea>
                        <label>Enter Discovery Information</label>
                        <span class="helper-text">Enter information about this lead.</span>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm mt-3 ladda pull-right" data-style="expand-left">
                        <i class="fa fa-edit"></i> Update Discovery
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <h6 class="mb-3">Questionnaire</h6>
        <p class="card-text text-muted">Below is a list of discovery items to help ensure your customer is prepared.</p>
        <table class="table">
            <thead>
            <tr>
                <th>Question</th>
                <th>Answer</th>
            </tr>
            </thead>
            <tbody>
            @foreach(\App\Models\Discovery::where('lead_type_id', $lead->lead_type_id)->get() as $d)
                <tr>
                    <td>{{$d->question}}</td>
                    <td>
                        <a class="live text-info" data-title="{{$d->question}}" href="/admin/leads/{{$lead->id}}/discovery/{{$d->id}}">
                            @if($lead->getDiscoveryAnswer($d))
                                {{$lead->getDiscoveryAnswer($d)}}
                            @else
                                No Answer
                            @endif
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="alert border-dark" role="alert">
            Customer Discovery URL: <br/> <a class="alert-link" target="_blank"
                                             href="{{$lead->discovery_link}}">
                {{$lead->discovery_link}}</a>
            @if($lead->contact && $lead->email)
                <br/><a href="/admin/leads/{{$lead->id}}/discovery/send" class="text-primary confirm pt-2"
                        data-message="Are you sure you want to send a discovery request to {{$lead->contact}} at {{$lead->email}}?"
                        data-method="GET"
                        data-confirm="Send Request">
                    <i class="fa fa-send"></i> Send Discovery Request
                </a>
            @endif
        </div>

    </div>
</div>

<div class="d-flex justify-content-between">
    <div class="card">
        <div class="card-body d-flex align-items-center">
            <div class="avatar rounded-circle no-thumbnail bg-light">
                <img class="img-fluid" src="/icons/1728946.png"></div>
            <div class="flex-fill ms-3 text-truncate">
                <div class="small text-uppercase">Potential MRR</div>
                <div><span class="h6 mb-0 fw-bold">${{moneyFormat($lead->primaryMrr,2)}}</span></div>
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-body d-flex align-items-center">
            <div class="avatar rounded-circle no-thumbnail bg-light">
                <img class="img-fluid" src="/icons/4395998.png"></div>
            <div class="flex-fill ms-3 text-truncate">
                <div class="small text-uppercase">Forecast Closing</div>
                <div><span
                        class="h6 mb-0 fw-bold">{{$lead->forecast_date ? $lead->forecast_date->format("M d, Y"): "Not Forecasted"}}</span>
                </div>
            </div>
        </div>
    </div>


</div>


<div class="card mt-3">
    <div class="card-header">
        <h6 class="card-title mb-0">Photos ({{$lead->activities()->whereNotNull('image_id')->count()}})</h6>

    </div>
    <div class="card-body">
        <div class="row g-1">
            @foreach($lead->activities()->whereNotNull('image_id')->get() as $img)
                <div class="col-4">
                    <a class="fancybox rounded d-block" rel="lightbox" href="{{_file($img->image_id)->relative}}">
                        <img class="img-fluid rounded" alt="" src="{{_file($img->image_id)->relative}}"/>
                    </a>
                </div>
            @endforeach

        </div>
    </div>
</div>
