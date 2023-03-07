@if(!$project->sent_on)
    <div class="alert alert-info alert-dismissible alert-label-icon rounded-label fade show" role="alert">
        <i class="ri-alert-line label-icon"></i><strong>Project Not Sent</strong> This project has not been sent to the
        customer for approval.
    </div>
@elseif(!$project->approved_on)
    <div class="alert alert-warning alert-dismissible alert-label-icon rounded-label fade show" role="alert">
        <i class="ri-alert-line label-icon"></i><strong>Project Not Approved</strong> This project has not been approved
        by the customer.
    </div>
@endif


<div class="d-flex align-items-center">
    <div class="avatar-xs flex-shrink-0">
        <span class="avatar-title bg-soft-success text-success rounded-2 fs-4">
            <i class="bx bxs-badge-dollar"></i>
        </span>
    </div>
    <div class="flex-grow-1 ms-3">
        <p class="text-uppercase fw-medium text-muted mb-3">{{$project->name}} Price</p>
        <h4 class="fs-5 mb-3">${{moneyFormat($project->totalMax)}}</h4>
        <div class="d-flex justify-content-between">
            <p class="text-muted mb-0">Min: <strong>${{moneyFormat($project->totalMin)}}</strong></p>
            <p class="text-muted mb-0">Max: <strong>${{moneyFormat($project->totalMax)}}</strong></p>
        </div>
    </div>
    <div class="flex-shrink-0 align-self-center">
        <span class="badge badge-soft-success fs-12">
            <i class="ri-arrow-up-s-line fs-13 align-middle me-1"></i> 22.96 %
        </span>
    </div>
</div>


<div class="d-flex align-items-center mt-4">
    <div class="avatar-xs flex-shrink-0">
        <span class="avatar-title bg-soft-warning text-warning rounded-2 fs-4">
            <i class="bx bxs-badge-dollar"></i>
        </span>
    </div>
    <div class="flex-grow-1 ms-3">
        <p class="text-uppercase fw-medium text-muted mb-3">{{$project->name}} Expenses</p>
        <h4 class="fs-5 mb-3">${{moneyFormat($project->totalExpenseMax)}}</h4>
        <div class="d-flex justify-content-between">
            <p class="text-muted mb-0">Min: <strong>${{moneyFormat($project->totalExpenseMin)}}</strong></p>
            <p class="text-muted mb-0">Max: <strong>${{moneyFormat($project->totalExpenseMax)}}</strong></p>
        </div>
    </div>
    <div class="flex-shrink-0 align-self-center">
        <span class="badge badge-soft-success fs-12">
            <i class="ri-arrow-up-s-line fs-13 align-middle me-1"></i>22.96 %
        </span>
    </div>
</div>


<div class="d-flex align-items-center mt-4">
    <div class="avatar-xs flex-shrink-0">
        <span class="avatar-title bg-soft-info text-info rounded-2 fs-4">
            <i class="bx bx-calendar"></i>
        </span>
    </div>
    <div class="flex-grow-1 ms-3">
        <p class="text-uppercase fw-medium text-muted mb-3">Project Duration</p>
        <h4 class="fs-5 mb-3">
            @if($project->start_date && $project->end_date)
                {{$project->start_date->diffForHumans($project->end_date, true)}}
            @else
                Set Start/End Dates
            @endif
        </h4>
    </div>

</div>



