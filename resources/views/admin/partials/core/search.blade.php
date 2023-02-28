<div>
    <form class="app-search d-none d-md-block">
        <div class="position-relative">
            <input type="text" class="form-control" placeholder="Search..." wire:model.debounce.500ms="query" autocomplete="off" wire:keydown.escape="disableSearch"
                   id="search-options" value="">
            <span class="mdi mdi-magnify search-widget-icon"></span>
            <span class="mdi mdi-close-circle search-widget-icon search-widget-icon-close d-none"
                  id="search-close-options"></span>
        </div>

        <div class="dropdown-menu dropdown-menu-lg search-result overflow-scroll" style="height: 800px; width: 600px;!important" id="search-dropdown">
            <div data-simplebar style="max-height: 320px;">
                <!-- item-->
                <div class="dropdown-header">
                    <h6 class="text-overflow text-muted mb-0 text-uppercase">Recent Activities</h6>
                </div>
                <div class="dropdown-item bg-transparent text-wrap">
                    @foreach($recentActions as $recent)
                        <a href="{{$recent->url}}" class="btn btn-soft-secondary btn-sm btn-rounded">{{$recent->title}}
                            <i class="mdi mdi-magnify ms-1"></i></a>
                    @endforeach
                </div>
                <!-- item-->
                <div class="dropdown-header mt-2">
                    <h6 class="text-overflow text-muted mb-1 text-uppercase">Results</h6>
                </div>

                @foreach($results as $idx => $result)
                    <a wire:click="sendTo({{$idx}})" href="#" class="dropdown-item notify-item">
                        <i class="ri-bubble-chart-line align-middle fs-18 text-muted me-2"></i>
                        <span>{{$result->title}}</span>
                        <br/>
                        <small class="text-muted">{!! $result->description !!}</small>
                    </a>
                @endforeach
            </div>
        </div>
    </form>


</div>
