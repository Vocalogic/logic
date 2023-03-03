@extends('layouts.admin', ['title' => $project->name, 'crumbs' => [
     '/admin/projects' => "Projects",
     $project->name
    ],
])

@section('content')
    <div class="row">


        <div class="col-lg-9 col-xs-12">
            @include('admin.projects.summary.description')
            @include('admin.projects.summary.widgets')
            @include('admin.projects.summary.categories')
        </div>

        <div class="col-lg-3 col-xs-12">
            @include('admin.projects.settings.index')

            <a class="btn btn-outline-info w-100" href="/admin/projects/{{$project->id}}/download"><i class="fa fa-download"></i> Download Project</a>
        </div>
    </div>



@endsection
