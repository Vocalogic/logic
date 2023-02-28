@foreach($account->alerts as $alert)
    <div class="col-lg-4 mt-2">
        <div class="mb-3 card border-{{$alert->type}}">
            <div class="card-header bg-{{$alert->type}} border-bottom-0 py-3">
                <h6 class="card-title">{{$alert->title}}</h6>
            </div>
            <div class="card-body">
                <p>
                    {{$alert->description}}
                </p>
                @if(isset($alert->url))
                    @if(preg_match("/\#/", $alert->url))
                        <a href="{{str_replace("#", '', $alert->url)}}" class="btn btn-{{$alert->type}} live"
                           data-title="{{$alert->action}}">{{$alert->action}}</a>
                    @else
                        <a href="{{$alert->url}}" class="btn btn-{{$alert->type}}">{{$alert->action}}</a>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endforeach
