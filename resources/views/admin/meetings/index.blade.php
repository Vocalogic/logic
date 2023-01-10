@extends('layouts.admin', ['title' => 'Meetings', 'crumbs' => [
     "Meetings",
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Scheduled Meetings and Events</h1>
            <small class="text-muted">Add new meetings and schedule recurring visits</small>
        </div>
    </div> <!-- .row end -->

@endsection
@section('content')

    <div class="row">
        <div class="col-lg-9">

            <div class="card">
                <div class="card-body">
                    <div class="lcal" id="lcal" data-url="/admin/events">

                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
