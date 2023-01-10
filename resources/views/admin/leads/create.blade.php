@extends('layouts.admin', ['title' => "New Lead", 'layout' => 2, 'crumbs' => [
    '/admin/leads' => "Leads",
    "Create Lead"

]])

@section('content')
    <div class="row">
        <div class="col-lg-12">
            @include('admin.leads.list')
        </div>
    </div>
@endsection
@section('right')
    @include('admin.leads.right')
@endsection
