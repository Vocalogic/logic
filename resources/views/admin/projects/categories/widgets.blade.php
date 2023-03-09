<div class="d-flex align-items-center">
    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-success text-success rounded-2 fs-2">
                            <i class="bx bxs-badge-dollar"></i>
                        </span>
    </div>
    <div class="flex-grow-1 ms-3">
        <p class="text-uppercase fw-medium text-muted mb-3">{{$category->name}} Price</p>
        <h4 class="fs-5 mb-3">${{moneyFormat($category->totalMax)}}</h4>
        <p class="text-muted mb-0">Minimum: ${{moneyFormat($category->totalMin)}}</p>
    </div>
    <div class="flex-shrink-0 align-self-center">
                        <span class="badge badge-soft-success fs-12"><i
                                class="ri-arrow-up-s-line fs-13 align-middle me-1"></i>22.96 %<span></span></span>
    </div>
</div>
<div class="d-flex align-items-center mt-4">
    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-warning text-warning rounded-2 fs-2">
                            <i class="bx bxs-badge-dollar"></i>
                        </span>
    </div>
    <div class="flex-grow-1 ms-3 mt-4">
        <p class="text-uppercase fw-medium text-muted mb-3"> Time Billed</p>
        <h4 class="fs-5 mb-3">${{moneyFormat($category->totalBilled)}}</h4>
    </div>
    <div class="flex-shrink-0 align-self-center">
        <span class="badge badge-soft-success fs-12">{{$category->progress}}%</span>
    </div>
</div>
<div class="d-flex align-items-center mt-4">
    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-info text-info rounded-2 fs-2">
                            <i class=" bx bx-calendar"></i>
                        </span>
    </div>
    <div class="flex-grow-1 ms-3">
        <p class="text-uppercase fw-medium text-muted mb-3">Category Duration</p>
        <h4 class="fs-5 mb-3">
            @if($category->start_date && $category->end_date)
                {{$category->start_date->diffForHumans($category->end_date, true)}}
            @else
                Set Start/End Dates
            @endif

        </h4>
    </div>

</div>

@include('admin.projects.categories.breakdown')
