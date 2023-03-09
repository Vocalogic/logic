@extends('layouts.admin', ['title' => $project->name . " | " . $project->company, 'crumbs' => [
     '/admin/projects' => "Projects",
     $project->name
    ],
])

@section('content')
    <div class="row">
        @if($project->status == \App\Enums\Core\ProjectStatus::Approved)
            <div class="col-lg-12">
                <div class="alert alert-primary alert-dismissible alert-additional fade show" role="alert">
                    <div class="alert-body">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <i class="ri-error-warning-line fs-16 align-middle"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="alert-heading">Project has been Approved !</h5>
                                <p class="mb-0">This project has been approved by the customer and is ready to
                                    start.</p>
                            </div>
                        </div>
                    </div>
                    <div class="alert-content">
                        <p class="mb-0">To begin, <a class='text-white text-decoration-underline'
                                                     href="/admin/projects/{{$project->id}}/start">click here to
                                start</a>
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-lg-9 col-xs-12">
            @include('admin.projects.summary.description')
            @include('admin.projects.summary.categories')
        </div>

        <div class="col-lg-3 col-xs-12">

            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#overview" role="tab">
                                Overview
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#settings" role="tab">
                                Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#actions" role="tab">
                                Actions
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">

                        <div class="tab-pane active" id="overview" role="tabpanel">
                            @include('admin.projects.summary.widgets')
                            @include('admin.projects.summary.catbreakdown')


                        </div>

                        <div class="tab-pane" id="settings" role="tabpanel">
                            @include('admin.projects.settings.index')
                        </div>

                        <div class="tab-pane" id="actions" role="tabpanel">
                            @include('admin.projects.actions')
                        </div>


                    </div>
                </div>
            </div>

            @if($project->unbilledTime && $project->account)
                <div class="card bg-primary">
                    <div class="card-body p-0">
                        @if(!$project->approved_on)
                            <div
                                class="alert alert-danger rounded-top alert-solid alert-label-icon border-0 rounded-0 m-0 d-flex align-items-center"
                                role="alert">
                                <i class="ri-error-warning-line label-icon"></i>
                                <div class="flex-grow-1 text-truncate">
                                    This project has not been approved.
                                </div>

                            </div>
                        @endif

                        <div class="row align-items-end">
                            <div class="col-sm-8">
                                <div class="p-3">
                                    <p class="fs-16 lh-base text-white">There is currently
                                        <span class="fw-semibold">${{moneyFormat($project->unbilledTime)}}</span> in
                                        unbilled time on this project.
                                    </p>
                                    <div class="mt-3">
                                        <a href="/admin/projects/{{$project->id}}/processtime"
                                           class="btn btn-info confirm"
                                           data-message="This will invoice all unbilled time, but will not send the invoice. This should be done manually after review."
                                           data-confirm="Generate Invoice"
                                           data-method="GET">Generate Invoice
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="px-3">
                                    <img src="/assets/images/user-illustarator-1.png" class="img-fluid" alt="">
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card-body-->
                </div>
            @endif

            @if($project->unbilledItems && $project->account)

                <div class="card">
                    <div class="card-body p-0">
                        <div class="row align-items-end">
                            <div class="col-sm-8">
                                <div class="p-3">
                                    <p class="fs-16 lh-base">There are currently <span class="fw-semibold">{{$project->unbilledItems}}</span> unbilled items on this project.</p>
                                    <div class="mt-3">
                                        <a href="/admin/projects/{{$project->id}}/unbilled" class="btn btn-info live"
                                        data-title="Create Invoice for Unbilled Items">Generate Invoice
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="px-3">
                                    <img src="/assets/images/user-illustarator-2.png" class="img-fluid" alt="">
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card-body-->
                </div>

            @endif



        </div>



@endsection
