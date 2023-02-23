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
                    <button class="btn btn-primary float-end btn-sm postButton" wire:click="save"><i
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
                    <div class="alert {{bma()}}warning">This comment will be made privately.
                        <a href="#" class="togglePrivate text-success" wire:click="togglePrivate" class="px-3">Make comment
                            public.</a>
                    </div>
                @else
                    <div class="alert {{bma()}}info">This comment will be viewable by the customer.
                        <a href="#" class="togglePrivate text-warning" wire:click="togglePrivate" class="px-3">Make comment
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

    <div wire:poll="updates">
        <div class="card">
            <div class="card-body">
                <div data-simplebar style="max-height: 850px;">
                    <div class="acitivity-timeline acitivity-main">
                        @foreach($activities as $act)
                            <div class="acitivity-item d-flex mt-3">
                                <div class="flex-shrink-0 avatar-xs acitivity-avatar">
                                    <div class="avatar-title bg-soft-success text-success rounded-circle">
                                        <i class="ri-message-line"></i>
                                    </div>
                                </div>

                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1"><b>{{$act->user->name}}</b>
                                        @if($act->event)
                                            added a calendar event
                                        @elseif($act->image_id)
                                            uploaded a photo
                                        @else
                                            added a comment
                                        @endif
                                        @if($act->private)
                                            <span class="text-danger bold"><i
                                                    class="fa fa-exclamation-circle"></i> Private</span>
                                        @endif

                                    </h6>
                                    <p class="text-muted mb-1">{!! nl2br($act->post) !!}</p>
                                    @if($act->image_id)
                                        <div class="mt-2">
                                            <a class="fancybox img-fluid" rel="ligthbox"
                                               href="{{_file($act->image_id)->relative}}">
                                                <img class="img-fluid rounded-4" alt=""
                                                     src="{{_file($act->image_id)->relative}}"/>

                                            </a>
                                        </div>
                                    @endif
                                    <small class="mb-0 text-muted pull-right">{{$act->created_at->diffForHumans()}}</small>


                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
