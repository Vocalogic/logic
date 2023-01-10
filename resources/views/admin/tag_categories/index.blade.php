@extends('layouts.admin', ['title' => "Tags",
'crumbs' => [
    "/admin/category/$category->id/items" => $category->name,
    'Tags'
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Tags in {{$category->name}}</h1>
            <small class="text-muted">Categorize your products and services for filtering.</small>
        </div>

    </div> <!-- .row end -->
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-4">
            @include('admin.tag_categories.cats')
        </div>
        <div class="col-lg-8">

        </div>
    </div>
@endsection
