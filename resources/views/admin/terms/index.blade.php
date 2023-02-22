@extends('layouts.admin', ['title' => 'TOS Manager', 'crumbs' => []])
@section('content')
    <div class="row">
        <div class="col-lg-2 col-xs-12">
            <a class="btn btn-primary w-100 btn-block" href="/admin/terms/create"><i class="fa fa-plus"></i> New Terms
            </a>
        </div>
        <div class="col-lg-10 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <td>Name</td>
                            <td>Products/Services Assigned</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(\App\Models\Term::all() as $term)
                            <tr>
                                <td><a href="/admin/terms/{{$term->id}}">{{$term->name}}</a></td>
                                <td>{{$term->items()->count()}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>

            </div>
        </div>
    </div>

@endsection
