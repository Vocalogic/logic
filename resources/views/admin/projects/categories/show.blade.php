@extends('layouts.admin', ['title' => $project->name . " | " . $category->name, 'crumbs' => [
     '/admin/projects' => "Projects",
     "/admin/projects/$project->id" => $project->name,
     $category->name
    ],
])
@section('content')

    <div class="row">
        <div class="col-lg-9 col-xs-12">
            @include('admin.projects.categories.tasks')

            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#discuss" role="tab">
                                Discussion ({{$category->comments}})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#billables" role="tab">
                                Billables ({{$category->items->count()}})
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="discuss" role="tabpanel">
                            <div
                                class="alert alert-secondary alert-dismissible alert-label-icon rounded-label fade show"
                                role="alert">
                                <i class="ri-check-double-line label-icon"></i><strong>Note:</strong>
                                Category discussions are customer viewable. Internal notes should be made directly on a
                                task.
                            </div>
                            @livewire('admin.thread-component', ['object' => $category])

                        </div>

                        <div class="tab-pane" id="billables" role="tab">
                            @include('admin.projects.categories.billables.index')
                        </div>
                    </div>
                </div>

            </div>
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
                            @include('admin.projects.categories.widgets')

                        </div>
                        <div class="tab-pane" id="settings" role="tabpanel">
                            @include('admin.projects.categories.settings')
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

@endsection
