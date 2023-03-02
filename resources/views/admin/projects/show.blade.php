@extends('layouts.admin', ['title' => $project->name, 'crumbs' => [
     '/admin/projects' => "Projects",
     $project->name
    ],
])

@section('content')
    <div class="row">
        <div class="col-lg-2 col-xs-12">
            @include('admin.projects.menu')
        </div>

        <div class="col-lg-7 col-xs-12">

            @include('admin.projects.summary.description')
            @include('admin.projects.summary.categories')
        </div>

        <div class="col-lg-3 col-xs-12">
            @include('admin.projects.settings.index')
        </div>
    </div>



@endsection
