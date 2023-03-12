<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <span class="fs-9">Project Min Price:</span>
            <span class="text-success fs-6">${{moneyFormat($project->totalMin)}}</span>
        </div>

        <div class="d-flex justify-content-between mt-3">
            <span class="fs-9">Project Max Price:</span>
            <span class="text-success fs-6">${{moneyFormat($project->totalMax)}}</span>
        </div>

        <div class="d-flex justify-content-between mt-3">
            <span class="fs-9">Start Date:</span>
            <span class="text-success fs-6">{{$project->start_date?->format("M d, Y")}}</span>
        </div>

        <div class="d-flex justify-content-between mt-3">
            <span class="fs-9">End Date:</span>
            <span class="text-success fs-6">{{$project->end_date?->format("M d, Y")}}</span>
        </div>

    </div>
</div>
