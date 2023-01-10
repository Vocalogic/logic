<div>

    <div class="card mb-3">
        <form wire:submit.prevent="save">
            <div class="card-body">
                <textarea class="form-control actInput" wire:model.defer='activity'
                          placeholder="Have a question/comment? Enter it here!" rows="4"></textarea>
                <div class="pt-3">
                    <a href="#" wire:click="togglePhoto" class="px-3"><i
                            class="fa fa-lg fa-camera text-primary"></i></a>


                    <button class="btn btn-sm add-button float-end" wire:click="save">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-edit">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        Add Note
                    </button>


                </div>
            </div>
            @if($photoMode)

                <div class="card-footer">
                    @if ($photo)
                        <img width=300 src="{{ $photo->temporaryUrl() }}">
                    @endif
                    <input type="file" wire:model.defer="photo" name="photo" class="drop"/>
                </div>
            @endif
        </form>

    </div> <!-- .Card End -->
    <div wire:poll="updates" class="overflow-auto" style="max-height: 700px;">
        <ul class="list-unstyled">
            @foreach($activities as $act)
                <li class="card mb-2">
                    <div class="card-header pb-2">
                        <div class="d-flex">
                            <img class="avatar rounded-circle" src="{{$act->user->avatar}}" alt="{{$act->user->name}}">
                            <div class="flex-fill ms-3 text-truncate">
                                <h6 class="mb-0">
                                    <span
                                        class="author">{{$act->user->name}} <small>({{$act->user->account->name}})</small></span>
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
