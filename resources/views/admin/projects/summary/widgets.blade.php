<div class="row">
    <div class="col-lg-4 col-sm-6">
        <div class="card card-height-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-success text-success rounded-2 fs-2">
                            <i class="bx bxs-badge-dollar"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-uppercase fw-medium text-muted mb-3">{{$project->name}} Price</p>
                        <h4 class="fs-4 mb-3">${{moneyFormat($project->totalMax)}}</h4>
                        <p class="text-muted mb-0">Minimum: ${{moneyFormat($project->totalMin)}}</p>
                    </div>
                    <div class="flex-shrink-0 align-self-center">
                        <span class="badge badge-soft-success fs-12"><i
                                class="ri-arrow-up-s-line fs-13 align-middle me-1"></i>22.96 %<span></span></span>
                    </div>
                </div>
            </div><!-- end card body -->
        </div>

    </div>


    <div class="col-lg-4 col-sm-6">
        <div class="card card-height-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-warning text-warning rounded-2 fs-2">
                            <i class="bx bxs-badge-dollar"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-uppercase fw-medium text-muted mb-3">{{$project->name}} Expenses</p>
                        <h4 class="fs-4 mb-3">${{moneyFormat($project->totalExpenseMax)}}</h4>
                        <p class="text-muted mb-0">Minimum: ${{moneyFormat($project->totalExpenseMin)}}</p>
                    </div>
                    <div class="flex-shrink-0 align-self-center">
                        <span class="badge badge-soft-success fs-12"><i
                                class="ri-arrow-up-s-line fs-13 align-middle me-1"></i>22.96 %<span></span></span>
                    </div>
                </div>
            </div><!-- end card body -->
        </div>
    </div>


    <div class="col-lg-4 col-sm-6">
        <div class="card card-height-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-info text-info rounded-2 fs-2">
                            <i class=" bx bx-calendar"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-uppercase fw-medium text-muted mb-3">Project Duration</p>
                        <h4 class="fs-4 mb-3">
                            @if($project->start_date && $project->end_date)
                                {{$project->start_date->diffForHumans($project->end_date, true)}}
                            @else
                                Set Start/End Dates
                            @endif

                        </h4>
                    </div>

                </div>
            </div><!-- end card body -->
        </div>
    </div>


</div>
