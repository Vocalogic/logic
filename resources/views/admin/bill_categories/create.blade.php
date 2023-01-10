@extends('layouts.admin', ['title' => "Create/Update Category", 'layout' => 2,
'crumbs' => [
    "/admin/bill_categories/$type" => ucfirst($type) . " Categories",
    ucfirst($type)
]])

@section('content')
    <div class="row">
        <div class="col-lg-12">
            @include('admin.bill_categories.list')
        </div>
    </div>
@endsection
@section('right')
    @include('admin.bill_categories.right')
@endsection
