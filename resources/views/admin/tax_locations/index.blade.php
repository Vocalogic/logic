@extends('layouts.admin', ['title' => "Tax Locations", 'crumbs' => [
     "Tax Locations",
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Tax Locations</h1>
            <small class="text-muted">Add default sales tax rates for different locations.</small>
        </div>

    </div> <!-- .row end -->

@endsection
@section('content')
    <div class="row">
        <div class="col-xs-12 col-lg-2">
            <a class="live btn btn-primary" data-title="Create new Tax Location" href="/admin/tax_locations/create">
                <i class="fa fa-plus"></i> New Tax Location
            </a>
        </div>

        <div class="col-lg-10">
            @include('admin.tax_locations.list')
        </div>
    </div>
@endsection
