<div>

    <div class="main-search px-3 flex-fill">
        <input class="form-control" type="text" wire:model.debounce.500ms="query" placeholder="Search....">
        <div class="card shadow rounded-4 search-result slidedown">
            <div class="card-body">
                <small class="text-uppercase text-muted">Recent Interests</small>
                <div class="d-flex flex-wrap align-items-start mt-2 mb-4">
                    @foreach($recentActions as $recent)
                        <a class="small rounded py-1 px-2 m-1 fw-normal {{$recent->class}}"
                           href="{{$recent->url}}">{{$recent->title}}</a>
                    @endforeach

                </div>
                <small class="text-uppercase text-muted">Suggestions</small>
                <div class="card list-group list-group-flush list-group-custom mt-2">
                    @foreach($results as $idx => $result)
                        <a wire:click="sendTo({{$idx}})" class="list-group-item list-group-item-action text-truncate" style="cursor: pointer;">
                            <div class="fw-bold">{{$result->title}}</div>
                            <small class="text-muted">{{$result->description}}</small>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>


</div>
