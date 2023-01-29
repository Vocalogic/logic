@extends('layouts.admin', ['title' => 'Manage Options', 'crumbs' => [
     "/admin/package_builds" => "Package Builds",
     "/admin/package_builds/$build->id/sections" => $build->name,
     "/admin/package_builds/$build->id/sections/$section->id/questions" => $section->name,
     "Manage Options"
]])

@section('content')
    <div class="row mb-3">
        <div class="col-lg-12">
            <h5 class="card-title">{{$question->question}}</h5>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-2">
            <a class="w-100 btn btn-block btn-primary live" data-title="Create new Option"
               href="/admin/package_builds/{{$build->id}}/sections/{{$section->id}}/questions/{{$question->id}}/options/create">
                <i class="fa fa-plus"></i> New Option
            </a>
        </div>

        <div class="col-lg-10">
            @switch($question->type)
                @case('select')
                    @include('admin.package_builds.sections.questions.options.select_list')
                @break
                @case('multi')
                    @include('admin.package_builds.sections.questions.options.multi_list')
                @break
                @case('product')
                    @include('admin.package_builds.sections.questions.options.product_list')
            @endswitch
        </div>
    </div>
@endsection
