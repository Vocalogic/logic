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
            @include('admin.projects.categories.widgets')
            @include('admin.projects.categories.billables.index')
        </div>
        <div class="col-lg-3 col-xs-12">
            @include('admin.projects.categories.settings')
        </div>
    </div>


@endsection
