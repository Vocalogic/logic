@extends('layouts.admin', ['title' => "{$cat->name} Tags",
'crumbs' => [
    "/admin/categories/$category->id/tag_categories" => $category->name . " Categories",
    "/admin/categories/$category->id/tag_categories/$cat->id/tags" => $cat->name,
    $tag->id ? $tag->name : "Create new Tag"
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">{{$tag->id ? $tag->name : "Create Tag "}} in {{$cat->name}} for {{$category->name}}</h1>
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
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{$tag->id ? "/admin/categories/$category->id/tag_categories/$cat->id/tags/$tag->id" : "/admin/categories/$category->id/tag_categories/$cat->id/tags"}}">
                        @method($tag->id ? "PUT" : "POST")
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="name"
                                           value="{{$tag->name}}">
                                    <label>Tag Name:</label>
                                    <span class="helper-text">Enter the name for this tag</span>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-12 col-md-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="description"
                                           value="{{$tag->description}}">
                                    <label>Short Tag Description:</label>
                                    <span class="helper-text">This information will be shown when a user hovers over the tag.</span>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-lg-12 col-md-12">
                                <div class="form-floating">
                                    {!! Form::select('relatable', [0 => 'No', 1 => 'Yes'], $tag->relatable, ['class' => "form-select"]) !!}
                                    <label>Tag Relatable?:</label>
                                    <span class="helper-text">If this tag is relatable, any item with this tag will be shown as a related product in the shop.</span>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-lg-12">
                                <input type="submit" name="submit" class="btn btn-{{bm()}}primary" value="Save Tag">
                                @if($tag->id)
                                    <a href="/admin/categories/{{$category->id}}/tag_categories/{{$cat->id}}/tags/{{$tag->id}}"
                                       class="confirm btn btn-{{bm()}}danger pull-right"
                                       data-message="Are you sure you want to remove this tag?"
                                       data-method="DELETE"><i class="fa fa-trash"></i> Remove Tag</a>
                                @endif

                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
