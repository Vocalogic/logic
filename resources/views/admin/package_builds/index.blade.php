@extends('layouts.admin', ['title' => 'Package Builds', 'crumbs' => [
     "Package Builds",
]])

@section('content')

    <div class="row">
        <div class="col-lg-2">
            <a class="w-100 btn btn-block btn-{{bm()}}primary live" data-title="Create new Package"
               href="/admin/package_builds/create">
                <i class="fa fa-plus"></i> New Package
            </a>
        </div>

        <div class="col-lg-10">
            <div class="card">
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                        <tr>
                            <th>Package</th>
                            <th>Sections</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(\App\Models\PackageBuild::all() as $build)
                            <tr>
                                <td><a href="/admin/package_builds/{{$build->id}}/sections">{{$build->name}}</a>
                                    <a class="live" data-title="Edit {{$build->name}}"
                                       href="/admin/package_builds/{{$build->id}}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </td>
                                <td>{{$build->sections()->count()}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
