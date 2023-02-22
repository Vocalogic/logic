@extends('layouts.admin', ['title' => $cat->name, 'crumbs' => $crumbs])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">{{$cat->name}}</h1>
            <small class="text-muted">{{$cat->description}}</small>
        </div>
    </div> <!-- .row end -->

@endsection
@section('content')
    <div class="row">
        <div class="col-lg-3">
            <a class="btn btn-primary live w-100" data-title="Add {{ucfirst(\Illuminate\Support\Str::singular($cat->type))}}" href="/admin/category/{{$cat->id}}/items/create"
               type="button"><i class="fa fa-plus"></i> New {{ucfirst(\Illuminate\Support\Str::singular($cat->type))}}
            </a>
            @include('admin.bill_items.subnav')
        </div>

        <div class="col-lg-9">
            @include('admin.bill_items.list')
        </div>
    </div>
@endsection
