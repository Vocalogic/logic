<div class="row">
    <div class="col-lg-9">

        <div class="card">
            <div class="card-body">
                <div class="lcal" id="lcal" data-url="/admin/events/leads/{{$lead->id}}">
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3">

        <ul class="list-unstyled submit-track">
            @foreach(\App\Models\Activity::where('refid', $lead->id)->where('type', 'LEAD')->whereNotNull('event')->get() as $event)
                @if($event->event < now())
                    <li class="is-complete">
                        <span class="badge date">{{$event->event->format("M d")}}</span>
                        <div class="circle"><i class="fa fa-check"></i></div>
                        <div class="box-title">
                            {{$event->post}} <a class="live" href="/admin/events/{{$event->id}}" data-title="Edit Event"><i class="fa fa-edit"></i></a>
                        </div>
                    </li>
                @else

                    <li class="">
                        <span class="badge date">{{$event->event->format("M d")}}</span>
                        <div class="circle"><i class="fa fa-calendar"></i></div>
                        <div class="box-title">
                            {{$event->post}} <a class="live" href="/admin/events/{{$event->id}}" data-title="Edit Event"><i class="fa fa-edit"></i></a>
                        </div>
                    </li>
                @endif
            @endforeach

        </ul>


    </div>

</div>
