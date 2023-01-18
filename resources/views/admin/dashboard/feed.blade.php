<div>
    <div wire:poll.5000ms="loadActivity" class="card">
        <div class="card-body"><h6 class="card-title">Recent Activity</h6>

            @foreach($activities as $act)

                <div class="timeline-item ti-success ms-2">
                    <div class="d-flex">
                        @if($act->user_id)
                            <img class="avatar sm rounded-circle" src="{{$act->user->avatar}}" alt="">
                        @endif
                        <div class="flex-fill ms-3">
                            <div class="mb-1">

                                @if($act->user_id && $act->user->account->id != 1)
                                    <span class="badge bg-{{bm()}}info">customer</span>
                                    @elseif($act->partner)
                                    <span class="badge bg-{{bm()}}primary">partner</span>
                                @endif
                                {!! $act->summary !!}
                                     <small class="text-muted">{{$act->created_at->diffForHumans()}}</small></div>
                            @if($act->post)
                                <div class="card p-3">
                                    @if(preg_match("/\<|\>/i", $act->post))
                                        {!! $act->post !!}
                                    @else
                                    {!! nl2br($act->post) !!}
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div> <!-- timeline item end  -->

            @endforeach

        </div>

    </div>
</div>
