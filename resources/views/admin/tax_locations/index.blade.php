@extends('layouts.admin', ['title' => "Tax Locations", 'crumbs' => [
     "Tax Locations",
]])

@section('content')
    <div class="row">
        <div class="col-xs-12 col-lg-2">
            <a class="live btn w-100 btn-primary" data-title="Create new Tax Location" href="/admin/tax_locations/create">
                <i class="fa fa-plus"></i> New Tax Location
            </a>
        </div>

        <div class="col-lg-10">
            @include('admin.tax_locations.list')
        </div>
    </div>
@endsection
