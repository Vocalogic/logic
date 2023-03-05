<div class="card card-height-100">
    <div class="card-body">
        <div class="d-flex flex-column h-100">
            <div class="d-flex">
                <div class="flex-grow-1">
                    <p class="text-muted mb-4">Duration:

                        @if($category->start_date && $category->end_date)
                            <span class="text-info">{{$category->start_date->diffForHumans($category->end_date, true)}}</span>
                        @else
                            <span class="text-warning"><i class="fa fa-exclamation"></i> No dates set</span>
                        @endif
                    </p>
                </div>

            </div>
            <div class="d-flex mb-2">
                <div class="flex-shrink-0 me-3">
                    <div class="avatar-sm">
                        <span class="avatar-title bg-soft-warning rounded p-2">
                            <img src="/assets/images/brands/slack.png" alt="" class="img-fluid p-1">
                        </span>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h5 class="mb-1 fs-15"><a href="/admin/projects/{{$project->id}}/categories/{{$category->id}}" class="text-dark">{{$category->name}}</a></h5>
                    <p class="text-muted text-truncate-two-lines mb-3">{{$category->description}}</p>
                </div>
            </div>
            <div class="mt-auto">
                <div class="d-flex mb-2">
                    <div class="flex-grow-1">
                        <div>Tasks</div>
                    </div>
                    <div class="flex-shrink-0">
                        <div><i class="ri-list-check align-bottom me-1 text-muted"></i> {{$category->completedTasks}}/{{$category->tasks->count()}}</div>
                    </div>
                </div>
                <div class="progress progress-sm animated-progress">
                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="{{$category->completedPercentage}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$category->completedPercentage}}%;"></div><!-- /.progress-bar -->
                </div><!-- /.progress -->
            </div>
        </div>

    </div>
    <!-- end card body -->
    <div class="card-footer bg-transparent border-top-dashed py-2">
        <div class="d-flex align-items-center">

            <div class="flex-grow-1">
                <div class="text-muted">
                    <i class="ri-calendar-event-fill me-1 align-bottom"></i> Created: {{$category->created_at->format("M d, Y")}}
                </div>
            </div>
            <div class="flex-shrink-0">
                <div class="text-muted">
                    <i class="ri-coin-line me-1 align-bottom"></i> $ {{moneyFormat($category->totalMin)}}
                </div>
            </div>

        </div>

    </div>
    <!-- end card footer -->
</div>
