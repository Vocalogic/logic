@extends('layouts.admin', ['title' => "{$category->name} Tags",
'crumbs' => [
    "/admin/category/$category->id/items" => $category->name,
     "/admin/categories/$category->id/tag_categories" => "Tag Categories",
     $category->name
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Tags in {{$category->name}} for {{$cat->name}}</h1>
            <small class="text-muted">Categorize your products and services for filtering.</small>
        </div>

    </div> <!-- .row end -->
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-4">
            @include('admin.tag_categories.cats')
        </div>
        <div class="col-lg-4">
            @include('admin.tag_categories.tags.list')
        </div>
    </div>
@endsection
