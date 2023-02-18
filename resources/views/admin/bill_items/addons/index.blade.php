@extends('layouts.admin', ['title' => "$item->name Addons",
    'crumbs' => $crumbs,
    'docs' => "https://logic.readme.io/docs/addons",
    'log' => $item->logLink
    ])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">{{$item->name}} Addons</h1>
            <small class="text-muted">{{$item->description ?: null}}</small>
        </div>
    </div> <!-- .row end -->
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-2">
            @include('admin.bill_items.menu')
        </div>
        <div class="col-xl-10">
            @include('admin.bill_items.addons.fields')
        </div>
    </div>

@endsection
