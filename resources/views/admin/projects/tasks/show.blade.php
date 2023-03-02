@extends('layouts.admin', ['title' => $task->name, 'crumbs' => [
     '/admin/projects' => "Projects",
     "/admin/projects/$project->id" => $project->name,
     "/admin/projects/$project->id/categories/{$task->category->id}" => $task->category->name,
     $task->name
    ],
])
@section('content')

    <div class="row">
        <div class="col-lg-9 col-xs-12">
            @include('admin.projects.tasks.summary.index')



            <div class="card">
                <div class="card-header">
                    <div>
                        <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#discuss" role="tab">
                                    Discussion (5)
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#attachments" role="tab">
                                    Attachments (4)
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#billables" role="tab">
                                    Billable Items (4)
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#time" role="tab">
                                    Time Entries (9 hrs 13 min)
                                </a>
                            </li>
                        </ul>
                        <!--end nav-->
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="discuss" role="tabpanel">
                            @include('admin.projects.tasks.summary.threads')
                        </div>
                        <div class="tab-pane" id="attachments">

                        </div>
                        <div class="tab-pane" id="billables">
                            @include('admin.projects.tasks.billables.index')
                        </div>
                        <div class="tab-pane" id="time">

                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-lg-3 col-xs-12">
            @include('admin.projects.tasks.settings.index')
        </div>

    </div>

@endsection
