@extends('layouts.admin', ['title' => $account->name, 'crumbs' => [
    '/admin/accounts' => "Accounts",
    "/admin/accounts/$account->id" => $account->name,
    'Projects'

], 'log' => $account->logLink])
@section('content')
    <div class="row">
        <div class="col-lg-2 col-xs-12">
            @include('admin.accounts.submenu')
        </div>

        <div class="col-lg-10 col-xs-12">
            <a data-title="Create new Project"
               class="btn btn-primary live"
               href="/admin/projects/create?account_id={{$account->id}}">
                <i class="fa fa-plus"></i> Create Project
            </a>
                    <div class="card mt-3">
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Account</th>
                                    <th>Status</th>

                                </tr>
                                </thead>
                                <tbody>
                                @foreach(\App\Models\Project::where('account_id', $account->id)->get() as $project)
                                    <tr>
                                        <td>
                                            <a href="/admin/projects/{{$project->id}}">
                                                <span class="badge badge-outline-primary">#{{$project->id}}</span>
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
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

    </div>
@endsection
