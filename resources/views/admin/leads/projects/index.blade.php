@extends('layouts.admin', ['title' => $lead->company, 'crumbs' => [
     '/admin/leads' => "Leads",
     "/admin/leads/$lead->id" => $lead->company,
     "Projects"
    ],
     'log' => $lead->logLink
])

@section('content')
    <div class="row">
        @include('admin.leads.profile.header')
    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            <a data-title="Create new Project"
               class="btn btn-primary live"
               href="/admin/projects/create?lead_id={{$lead->id}}">
                <i class="fa fa-plus"></i> Create Project
            </a>

            <table class="table mt-3 table-striped">
                <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($lead->projects as $project)
                    <tr>
                        <td>
                            <a href="/admin/projects/{{$project->id}}>"<span class="badge badge-outline-primary">{{$project->id}}</span></td>
                        </td>
                        <td>{{$project->name}}</td>
                        <td>{{$project->description}}</td>
                        <td>{{$project->status->value}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

@endsection
