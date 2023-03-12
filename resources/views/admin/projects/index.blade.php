@extends('layouts.admin', ['title' => 'Projects', 'crumbs' => [
     'Projects'
    ],
])

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped">
                        <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Account</th>
                            <th>Status</th>
                            <th>Unbilled</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach(\App\Models\Project::all() as $project)
                            <tr>
                                <td>
                                    <a href="/admin/projects/{{$project->id}}">
                                        <span class="badge badge-outline-info">#{{$project->id}}</span>
                                    </a>
                                </td>
                                <td>
                                    {{$project->name}}<br/>
                                    <span class="small text-muted">{{$project->description}}</span>
                                </td>
                                <td>
                                    @if($project->lead)
                                        <a class='link-primary' href="/admin/leads/{{$project->lead->id}}">
                                            {{$project->lead->company}}
                                        </a>
                                        <span class="badge badge-outline-warning">lead</span>
                                    @else
                                        <a class='link-primary' href="/admin/accounts/{{$project->account->id}}">
                                            {{$project->account->name}}
                                        </a>
                                    @endif
                                </td>
                                <td>{{$project->status->value}}</td>
                                <td>${{moneyFormat($project->unbilledTime)}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

@endsection
