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

        <div class="col-lg-12 mt-2">
            <div class="card mb-3 fieldset border border-muted">
                <span class="fieldset-tile text-muted bg-body">Logs</span>
                <div class="card-body">

                </div>
            </div>
        </div>
    </div>
@endsection


