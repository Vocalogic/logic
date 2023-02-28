@extends('layouts.admin', ['title' => 'File Categories', 'crumbs' => [
     "Lead Types",
]])

@section('content')
    <div class="row">
        <div class="col-xs-12 col-md-2">
            <a class="btn w-100 btn-primary" href="/admin/file_categories/create"><i class="fa fa-plus"></i> New Category</a>

        </div>
        <div class="col-lg-10 col-xs-12">
            <div class="card">
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

                </div>
            </div>

        </div>

    </div>
@endsection
