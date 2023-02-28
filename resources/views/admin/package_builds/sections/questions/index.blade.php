@extends('layouts.admin', ['title' => 'Package Builds', 'crumbs' => [
     "/admin/package_builds" => "Package Builds",
     "/admin/package_builds/$build->id/sections" => $build->name,
     "$section->name Questions"
]])

@section('content')

    <div class="row">
        <div class="col-lg-2">
            <a class="w-100 btn btn-block btn-primary live" data-title="Create new Question"
               href="/admin/package_builds/{{$build->id}}/sections/{{$section->id}}/questions/create">
                <i class="fa fa-plus"></i> New Question
            </a>
        </div>

        <div class="col-lg-10">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Question</th>
                            <th>Type</th>
                            <th>Default Mode</th>
                            <th>Options</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($section->questions as $question)
                            <tr>
                                <td>
                                    <a class="live" data-title="Edit {{$question->name}}"
                                       href="/admin/package_builds/{{$build->id}}/sections/{{$section->id}}/questions/{{$question->id}}">
                                        {{$question->question}}
                                    </a>
                                </td>
                                <td>{{$question->type}}</td>
                                <td>{{$question->default_show ? "Show" : "Do not Show"}}</td>
                                <td>
                                    @if($question->type == 'multi' || $question->type == 'select' || $question->type == 'product')
                                    <a href="/admin/package_builds/{{$build->id}}/sections/{{$section->id}}/questions/{{$question->id}}/options">Options</a> |
                                    @endif
                                    <a href="/admin/package_builds/{{$build->id}}/sections/{{$section->id}}/questions/{{$question->id}}/logics">Quote Logic</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
