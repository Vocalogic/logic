@extends('layouts.admin', ['title' => "Commission Report", 'crumbs' => [
     "Commissions",
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Outstanding Commission Report</h1>
            <small class="text-muted">Show commissions and their status</small>
        </div>
    </div> <!-- .row end -->

@endsection

@section('content')
    <div class="row">
        <div class="col-lg-3 col-xs-12 mt-2">
            @include('admin.finance.commissions.sidebar')
        </div>
        <div class="col-lg-9 col-xs-12 mt-2">
            <div class="card">
                <div class="card-body">
                    @include('admin.finance.commissions.list')
                </div>
            </div>
        </div>
    </div>
@endsection
