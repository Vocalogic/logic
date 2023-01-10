<div>

    <div class="card mb-3">
        <form wire:submit.prevent="save">
            <div class="card-body">
                <textarea class="form-control actInput" wire:model.defer='activity'
                          placeholder="Enter activity update/note" rows="4"></textarea>
                <div class="pt-3">
                    <a href="#" wire:click="togglePhoto" class="px-3"><i class="fa fa-camera"></i></a>

                    @if(!$guest)
                        <a href="#" wire:click="toggleCalendar" class="px-3"><i class="fa fa-calendar"></i></a>
                    @endif
                    <button class="btn btn-{{bm()}}primary float-end postButton" wire:click="save"><i
                            class="fa fa-plus"></i> Post
                        Update
                    </button>
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
                    <input type="datetime-local" class="form-control form-control-lg" placeholder="Select Event Date"
                           wire.defer="date">
                @endif

                @if($private)
                    <div class="alert {{bma()}}warning">This comment will be made privately and will not be shown to the
                        customer.
                        <a href="#" class="togglePrivate" wire:click="togglePrivate" class="px-3">Make comment
                            public.</a>
                    </div>
                @else
                    <div class="alert {{bma()}}info">This comment will be viewable by the customer.
                        <a href="#" class="togglePrivate" wire:click="togglePrivate" class="px-3">Make comment
                            private.</a>
                    </div>
                @endif
                @if($partnerMode)
                        <div class="alert {{bma()}}warning">This comment will be seen by <b>{{$lead->partner->name}}</b>
                            as they are the partner on record for this lead. (regardless of privacy status)
                        </div>
                @endif


            </div>
        </form>

    </div> <!-- .Card End -->
    <div wire:poll="updates" class="overflow-auto" style="max-height: 700px;">
        <ul class="list-unstyled">
            @foreach($activities as $act)
                <li class="card mb-2">
                    <div class="card-header pb-0">
                        <div class="d-flex">
                            @if($act->user)
                            <img class="avatar rounded-circle" src="{{$act->user->avatar}}" alt="{{$act->user->name}}">
                            <div class="flex-fill ms-3 text-truncate">
                                <h6 class="mb-0">
                                    <span class="author">{{$act->user->name}}</span>
                                    <small class="text-muted">
                                        @if($act->event)
                                            added a calendar event
                                        @elseif($act->image_id)
                                            uploaded a photo
                                        @else
                                            added a comment
                                        @endif
                                        @if($act->private)
                                            <span class="text-danger bold"><i class="fa fa-exclamation-circle"></i> Private</span>
                                        @endif
                                    </small>
                                </h6>
                                <small class="text-muted">{{$act->created_at->diffForHumans()}}</small>
                            </div>
                                @elseif($act->partner)
                                <div class="flex-fill ms-3 text-truncate">
                                    <h6 class="mb-0">
                                        <span class="author">{{$act->partner->name}}</span>
                                        <small class="text-muted">
                                            @if($act->event)
                                                added a calendar event
                                            @elseif($act->image_id)
                                                uploaded a photo
                                            @else
                                                added a comment
                                            @endif
                                            @if($act->private)
                                                <span class="text-danger bold"><i class="fa fa-exclamation-circle"></i> Private</span>
                                            @endif
                                        </small>
                                    </h6>
                                    <small class="text-muted">{{$act->created_at->diffForHumans()}}</small>
                                </div>

                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="post-detail">
                            <p class="lead" style="font-size: 16px;">{!! nl2br($act->post) !!}</p>


                            @if($act->event)
                                <div role="alert"
                                     class="alert {{currentMode() == 'dark' ? "bg-light-info" : "alert-primary"}}"><i
                                        class="fa fa-calendar"></i> A
                                    calendar event was added for
                                    @if($mode == 'LEAD')
                                        {{$lead->company}}
                                    @elseif($mode == 'ACCOUNT')
                                        {{$account->name}}
                                    @elseif($mode == 'ORDER')
                                        {{$order->account->name}}
                                    @elseif($mode == 'PROV')
                                        {{$provisioning->account->name}}
                                    @endif
                                    for {{$act->event->calendar()}}</div>
                            @endif

                            @if($act->image_id)
                                <a class="fancybox img-fluid" rel="ligthbox" href="{{_file($act->image_id)->relative}}">
                                    <img class="img-fluid rounded-4" alt="" src="{{_file($act->image_id)->relative}}"/>
                                </a>
                            @endif
                        </div>
                    </div>
                </li>
            @endforeach


        </ul>
    </div>

</div>
