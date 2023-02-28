<div>

    <div class="card mb-3">
        <form wire:submit.prevent="save">
        <div class="card-body">
            <textarea class="form-control" wire:model.defer='activity' placeholder="Enter activity update or calendar note with date." rows="4"></textarea>
            <div class="pt-3">
                <a href="#" wire:click="togglePhoto" class="px-3"><i class="fa fa-camera"></i></a>
                <a href="#" wire:click="toggleCalendar" class="px-3"><i class="fa fa-calendar"></i></a>
                <button class="btn btn-primary float-end" wire:click="save"><i class="fa fa-plus"></i> Post Update</button>
            </div>
        </div>

        <div class="card-footer">
            @if($photoMode)
                @if ($photo)
                    <img width=300 src="{{ $photo->temporaryUrl() }}">
                @endif
                <input type="file" wire:model.defer="photo" name="photo" class="drop"/>
            @endif

            @if($calendarMode)
                <input type="text" class="dtrange form-control" wire:model.defer="daterange">
            @endif
        </div>
        </form>

    </div> <!-- .Card End -->
    <ul class="list-unstyled">
        @foreach($activities as $act)
        <li class="card mb-2">
            <div class="card-header pb-0">
                <div class="d-flex">
                    <img class="avatar rounded-circle" src="{{$act->user->avatar}}" alt="{{$act->user->name}}">
                    <div class="flex-fill ms-3 text-truncate">
                        <h6 class="mb-0">
                            <span class="author">{{$act->user->name}} @if($act->user->id == $lead->agent_id) (owner) @endif</span>
                            <small class="text-muted">
                                @if($act->event)
                                added a calendar event
                                @elseif($act->image_id)
                                uploaded a photo
                                @else
                                added activity
                                @endif
                            </small>
                        </h6>
                        <small class="text-muted">{{$act->created_at->diffForHumans()}}</small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="post-detail">
                    <p class="lead">{{nl2br($act->post)}}</p>

                    @if($act->event)
                        <div role="alert" class="alert alert-dark"><i class="fa fa-calendar"></i> A calendar event was added for {{$lead->company}} for {{$act->event->calendar()}}</div>
                    @endif

                    @if($act->image_id)
                        <a class="fancybox img-fluid" rel="ligthbox" href="{{_file($act->image_id)->relative}}">
                            <img class="img-fluid rounded-4" alt="" src="{{_file($act->image_id)->relative}}" />
                        </a>
                    @endif
                </div>
            </div>
        </li>
        @endforeach





    </ul>

</div>
