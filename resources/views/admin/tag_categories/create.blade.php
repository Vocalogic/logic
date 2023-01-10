@extends('layouts.admin', ['title' => "Create/Edit Category",
'crumbs' => [
 "/admin/categories/$category->id/tag_categories" => $category->name . " Categories",
     $cat->id ? "Edit $cat->name" : "Create New Tag Category"
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">{{$cat->id ? "Edit $cat->name" : "Create New Tag"}} in {{$cat->name}}</h1>
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
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{$cat->id ? "/admin/categories/$category->id/tag_categories/$cat->id" : "/admin/categories/$category->id/tag_categories"}}">
                        @method($cat->id ? "PUT" : "POST")
                        @csrf
                        <div class="row">
                            <div class="col-lg-4 col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="name"
                                           value="{{$cat->name}}">
                                    <label>Tag Name:</label>
                                    <span class="helper-text">Enter the name for this tag</span>
                                </div>
                            </div>

                            <div class="col-lg-8 col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="description"
                                           value="{{$cat->description}}">
                                    <label>Short Tag Description:</label>
                                    <span class="helper-text">This information will be shown when a user hovers over the tag.</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-floating">
                                    {!! Form::select('filter_cat', [1 => 'Yes', 0 => 'No'], $cat->filter_cat, ['class' => 'form-select']) !!}
                                    <label>Shop Filter Category:</label>
                                    <span class="helper-text">Should this category be displayed as a filter category in the shop?</span>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-lg-12">
                                <input type="submit" name="submit" class="btn btn-{{bm()}}primary" value="Save Category">

                            @if($cat->id)
                                <a href="/admin/categories/{{$category->id}}/tag_categories/{{$cat->id}}"
                                class="confirm btn btn-{{bm()}}danger pull-right"
                                data-message="Are you sure you want to remove this tag category and all its corresponding tags?"
                                data-method="DELETE"><i class="fa fa-trash"></i> Remove Tag Category</a>
                            @endif

                            </div>

                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
