<div>

    <div class="card border-0 w380">
        <div class="card-header p-3">
            <h6 class="card-title mb-0">Notifications Center</h6>
            <span class="badge bg-{{user()->unread ? 'danger' : 'success'}} text-light">{{user()->unread}}</span>
        </div>
        <div class="card-body">
            @if(!user()->unread)
                <h4 class="color-400">No new notifications</h4>
            @else
            <ul class="list-unstyled list mb-0">
                @foreach(user()->notifications()->where('read', false)->orderBy('created_at', 'DESC')->take(10)->get() as $note)

                    <li class="py-2 mb-1 border-bottom">
                        <a href="{{$note->link}}" class="d-flex">
                            <i class="fa {{$note->category->getIcon()}}"></i>
                                <div class="flex-fill ms-3">
                                    <p class="d-flex justify-content-between mb-0"><span>{{$note->title}}</span>
                                        <small>{{$note->created_at->format("m/d h:ia")}}</small></p>
                                    <span>{!! $note->message !!}</span>
                                </div>
                        </a>
                    </li>
                @endforeach
            </ul>
            @endif
        </div>
    </div>
    <a href="#" wire:click="markRead" class="btn btn-primary d-flex flex-grow text-light rounded-0 text-center">Mark all Read </a>
</div>
