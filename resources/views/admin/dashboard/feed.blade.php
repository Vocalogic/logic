<div>
    <div wire:poll.5000ms="loadActivity" class="card">

        <div class="card-body"><h6 class="card-title">Recent Activity</h6>
            <div class="acitivity-timeline acitivity-main">

                @foreach($activities as $act)

                    <div class="acitivity-item d-flex mb-3">
                        @if($act->user_id)
                            <div class="flex-shrink-0 avatar-xs acitivity-avatar">
                                <img class="avatar sm rounded-circle" src="{{$act->user->avatar}}" alt="">
                            </div>
                        @endif

                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">{!! $act->summary !!}</h6>
                            <p class="text-muted mb-1">{!! $act->post !!}</p>
                            <small class="mb-0 text-muted">{{$act->created_at->format("m/d/y h:ia")}}</small>
                        </div>
                    </div>

                @endforeach
            </div>

        </div>
    </div>


</div>
