@extends('layouts.admin', ['title' => "Shipments", 'crumbs' => [
     "Shipments",
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Shipments</h1>
            <small class="text-muted">Physical product shipment dashboard.</small>
        </div>

    </div> <!-- .row end -->

@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            @include('admin.shipments.list')
        </div>
    </div>
@endsection
