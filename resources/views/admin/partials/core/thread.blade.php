<div>
        @if(!$commentReply)
        <div class="row">
            <div class="col-lg-12">
                <textarea  wire:model="newComment" class="form-control bg-light border-light" id="newComment" rows="3" placeholder="Enter Comment"></textarea>
            </div>
            @if($uploadVisible)
                <div class="col-lg-12">
                        <input type="file" wire:model.defer="file" name="file" class="drop"/>
                </div>
            @endif
            <div class="col-12 text-end">
                <a class="btn btn-ghost-secondary btn-icon waves-effect me-1" wire:click="toggleUpload" href="#"><i class="ri-attachment-line fs-16"></i></a>
                <a href="#" wire:click="addRootComment" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Comment</a>
            </div>
        </div>
        @endif

        @foreach($thread->comments()->whereNull('thread_comment_id')->orderBy('created_at', 'DESC')->get() as $comment)
            <div class="d-flex mb-4">
                <div class="flex-shrink-0">
                    <img src="{{$comment->user?->avatar}}" alt="" class="avatar-xs rounded-circle"/>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h5 class="fs-13"><a href="#">{{$comment->user ? $comment->user->name : "Customer"}}</a> <small
                            class="text-muted">{{$comment->created_at->format("m/d/y h:ia")}}</small></h5>
                    <p class="text-muted">{!! nl2br($comment->comment) !!}</p>
                    @if($comment->files()->count())
                        <div class="row g-2 mb-3">
                            @foreach($comment->files as $file)
                            <div class="col-lg-2 col-sm-6">
                                @if(preg_match("/image/", $file->file->mime_type))
                                <img src="{{_file($file->file_id)->relative}}" alt="" class="img-fluid rounded">
                                    @else
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm">
                                            <div class="avatar-title bg-soft-primary text-primary rounded fs-20">
                                                <i class="{{_file($file->id)->icon}}"></i>
                                            </div>
                                        </div>
                                        <div class="ms-3 flex-grow-1">
                                            <h6 class="fs-8 mb-0"><a href="#">{{_file($file->file_id)->name}}</a></h6>
                                        </div>
                                    </div>
                                    @endif
                            </div>
                            @endforeach
                        </div>
                    @endif
                    <a href="#" wire:click="toggleCommentReply({{$comment->id}})" class="badge text-muted bg-light"><i class="mdi mdi-reply"></i> Reply</a>
                    @if($commentReply && $commentReply == $comment->id)
                        <div class="row mt-2">
                            <div class="col-lg-12">
                                <textarea  wire:model="newCommentReply" class="form-control bg-light border-light" id="newCommentReply" rows="3" placeholder="Enter Comment"></textarea>
                            </div>
                            @if($uploadVisible)
                                <div class="col-lg-12">
                                    <input type="file" wire:model.defer="file" name="file" class="drop"/>
                                </div>
                            @endif
                            <div class="col-12 text-end">
                                <a class="btn btn-ghost-secondary btn-icon waves-effect me-1" wire:click="toggleUpload" href="#"><i class="ri-attachment-line fs-16"></i></a>
                                <a href="#" wire:click="addCommentReply({{$comment->id}})" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Comment</a>
                            </div>
                        </div>
                    @endif
                    @foreach($comment->children()->orderBy('created_at', 'DESC')->get() as $child)
                        <div class="d-flex mt-4">
                            <div class="flex-shrink-0">
                                <img src="{{$child->user?->avatar}}" alt="" class="avatar-xs rounded-circle"/>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="fs-13"><a href="#">{{$child->user? $child->user->name : "Customer"}}</a> <small
                                        class="text-muted">{{$child->created_at->format("m/d/y h:ia")}}</small></h5>
                                <p class="text-muted">{!! nl2br($child->comment) !!}</p>
                                @if($child->files()->count())
                                    <div class="row g-2 mb-3">
                                        @foreach($child->files as $file)
                                            <div class="col-lg-2 col-sm-6">
                                                @if(preg_match("/image/", $file->file->mime_type))
                                                    <img src="{{_file($file->file_id)->relative}}" alt="" class="img-fluid rounded">
                                                @else
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm">
                                                            <div class="avatar-title bg-soft-primary text-primary rounded fs-20">
                                                                <i class="{{_file($file->id)->icon}}"></i>
                                                            </div>
                                                        </div>
                                                        <div class="ms-3 flex-grow-1">
                                                            <h6 class="fs-8 mb-0"><a href="#">{{_file($file->file_id)->name}}</a></h6>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

</div>
