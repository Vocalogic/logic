<div>
    <div wire:poll.5000ms="loadActivity" class="card">
        <div class="card-header border-bottom-dashed align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Recent Activity</h4>
            <div class="flex-shrink-0">
                <button type="button" class="btn btn-soft-primary btn-sm">
                    View All Activity
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div data-simplebar style="max-height: 964px;" class="p-3">


                <div class="acitivity-timeline acitivity-main">

                    @foreach($activities as $act)

                        <div class="acitivity-item d-flex mb-3">
                            @if($act->user_id)
                                <div class="flex-shrink-0 avatar-xs acitivity-avatar mr-3">
                                    <img class="img-fluid rounded-circle" src="{{$act->user->avatar}}" alt="">
                                </div>
                                @else
                                <div class="flex-shrink-0 avatar-xs acitivity-avatar mr-3">
                                    <img class="img-fluid rounded-circle" src="/assets/images/vlavatar.png" alt="">
                                </div>
                            @endif


                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">{!! $act->summary !!}</h6>
                                <p class="text-muted mb-1">{!! $act->post !!}</p>
                                <small class="mb-0 text-muted pull-right">{{$act->created_at->format("m/d/y h:ia")}}</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>


</div>
