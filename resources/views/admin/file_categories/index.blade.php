@extends('layouts.admin', ['title' => 'File Categories', 'crumbs' => [
     "Lead Types",
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">File Categories</h1>
            <small class="text-muted">Select the folders for each customer for uploading files.</small>
        </div>
    </div> <!-- .row end -->

@endsection
@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card border-primary">
                <div class="card-body">
                    <h6 class="card-title">File Categories</h6>
                    <p>
                        <code>File Categories</code> are used to categorize the different types of files you want to track
                        for a customer. Each category will only allow a specific type of file.
                    </p>

                    <table class="table mt-2">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Public by Default</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach(\App\Models\FileCategory::orderBy('name')->get() as $cat)
                            <tr>
                                <td><a href="/admin/file_categories/{{$cat->id}}">{{$cat->name}}</a></td>
                                <td>{{$cat->type->getHuman()}}</td>
                                <td>{{$cat->default_public ? "Yes" : "No"}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <a class="btn btn-{{bm()}}info" href="/admin/file_categories/create"><i class="fa fa-plus"></i> new file category</a>
                </div>
            </div>

        </div>

    </div>
@endsection
