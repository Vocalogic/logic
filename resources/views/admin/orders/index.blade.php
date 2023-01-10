@extends('layouts.admin', ['title' => "Open Orders", 'crumbs' => [
     "Orders",
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Open Orders</h1>
            <small class="text-muted">All open orders and logistics tracking</small>
        </div>

    </div> <!-- .row end -->

@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12 mt-2">
            @include('admin.orders.list')
        </div>
    </div>
@endsection
