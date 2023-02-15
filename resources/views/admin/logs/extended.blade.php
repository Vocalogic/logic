@extends('layouts.admin', ['title' => "Extended Log View for {$modelName} #{$entity->id}", 'crumbs' => [
     "{$modelName} Logs #{$entity->id}",
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Extended Log View for {{$modelName}} #{{$entity->id}}</h1>
        </div>
    </div> <!-- .row end -->

@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div>

            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <table class="table table-striped table-sm small">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Account</th>
                            <th>Level</th>
                            <th>Message</th>
                            <th>Details</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($logs as $log)
                            <tr>
                                <td>{{$log->created_at}}</td>
                                <td>{{$log->account?->name}}</td>
                                <td>{{\App\Enums\Core\LogSeverity::from($log->log_level)->getShort()}}</td>
                                <td>{{$log->log}}</td>
                                <td>{{$log->detail}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


