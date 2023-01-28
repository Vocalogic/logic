@extends('layouts.admin', ['title' => 'Package Builds', 'crumbs' => [
     "/admin/package_builds" => "Package Builds",
     $build->name
]])

@section('content')

    <div class="row">
        <div class="col-lg-2">
            <a class="w-100 btn btn-block btn-{{bm()}}primary live" data-title="Create new Section"
               href="/admin/package_builds/{{$build->id}}/sections/create">
                <i class="fa fa-plus"></i> New Section
            </a>
        </div>

        <div class="col-lg-10">
            <div class="card">
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                        <tr>
                            <th>Section Name</th>
                            <th>Questions</th>
                            <th>Default Mode</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($build->sections as $section)
                            <tr>
                                <td><a href="/admin/package_builds/{{$build->id}}/sections/{{$section->id}}/questions">{{$section->name}}</a>
                                    <a class="live pull-right" data-title="Edit {{$section->name}}"
                                       href="/admin/package_builds/{{$build->id}}/sections/{{$section->id}}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <br/><small class="text-muted">{{$section->description}}</small>
                                </td>
                                <td>{{$section->questions()->count()}}</td>
                                <td>{{$section->default_show ? "Show" : "Do not Show"}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
