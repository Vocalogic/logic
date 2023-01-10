@extends('layouts.admin', ['title' => 'TOS Manager'])


@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Terms of Service Manager</h1>
            <small class="text-muted">Manage your Terms of Service for different types of accounts</small>
        </div>

    </div> <!-- .row end -->

@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <td>Name</td>
                            <td>Type</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(\App\Models\Term::all() as $term)
                            <tr>
                                <td><a href="/admin/terms/{{$term->id}}">{{$term->name}}</a></td>
                                <td>{{$term->type->name}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>

            </div>
        </div>
    </div>

@endsection
