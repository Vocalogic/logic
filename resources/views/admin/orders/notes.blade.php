<div class="row">
    <div class="col-12">
    <p class="card-text"><code>NOTE:</code> These notes are not customer-viewable and are used for internal purposes only. Customers will be allowed
    to see all public comments on the order itself, just not the notes per item.</p>
        <ul class="list-unstyled mb-0 mt-2">
            @foreach($item->notes()->orderBy('created_at', 'DESC')->get() as $note)
                <!-- Chat: left -->
                <li class="mb-3 d-flex flex-row align-items-end">
                    <div class="max-width-70">
                        <div class="user-info mb-1">
                            <img class="avatar xs rounded-circle me-1"
                                 src="{{$note->user ? $note->user->avatar : "/assets/images/xs/avatar1.jpg"}}"
                                 alt="avatar">
                            <span
                                class="text-muted small">{{$note->user ? $note->user->short : "Unknown"}}, {{$note->created_at->format("M d h:ia")}}</span>
                        </div>
                        <div class="card p-3">
                            <div class="message">{!! nl2br($note->note) !!}</div>
                        </div>
                    </div>
                    <!-- More option -->
                </li>
            @endforeach
        </ul>
        <div class="chat-msg">
            <form method="post" action="/admin/orders/{{$order->id}}/items/{{$item->id}}/notes">
                @csrf
                @method('POST')
                <textarea style="height: 150px" class="form-control bg-transparent" placeholder="Enter new note here..."
                          name="note"></textarea>
                <input class="btn btn-sm mt-3 bg-secondary text-light text-uppercase" type='submit' name="send"
                       value="Add Note">
                @if($item->status != 'Complete')
                    <a href="/admin/orders/{{$order->id}}/items/{{$item->id}}/close" class="mt-3 ml-3 confirm btn btn-{{bm()}}info"
                   data-message="Are you sure you want to mark this as completed?"
                   data-method="POST"><i class="fa fa-check"></i> Mark Completed</a>
                @endif
            </form>
        </div>
    </div>
</div>


