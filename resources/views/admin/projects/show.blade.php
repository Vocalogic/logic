@extends('layouts.admin', ['title' => $project->name, 'crumbs' => [
     '/admin/projects' => "Projects",
     $project->name
    ],
])

@section('content')
    <div class="row">


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
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">

                        <div class="tab-pane active" id="overview" role="tabpanel">
                            @include('admin.projects.summary.widgets')

                        </div>

                        <div class="tab-pane" id="settings" role="tabpanel">
                            @include('admin.projects.settings.index')
                        </div>


                    </div>
                </div>
            </div>

            <a class="btn btn-outline-info w-100 ladda" data-style="zoom-in"
               href="/admin/projects/{{$project->id}}/download">
                <i class="fa fa-download"></i> Download Project
            </a>

            <a class="btn btn-outline-info w-100 ladda mt-3" data-style="zoom-in"
               href="/admin/projects/{{$project->id}}/msa">
                <i class="fa fa-building"></i> Edit MSA
            </a>

            <a class="btn btn-outline-success w-100 confirm mt-3" href="/admin/projects/{{$project->id}}/send"
               data-method="GET"
               data-message="Are you sure you want to send this project to the customer for review?">
                <i class="fa fa-send"></i> Send to Customer
            </a>
        </div>

@endsection
