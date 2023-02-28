@extends('layouts.admin', ['title' => $category->name ?: "Create new Category", 'crumbs' => [
     '/admin/file_categories' => "File Categories",
     $category->name ?: "Create new Category"
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">File Categories
            @if($category->id) - {{$category->name}} @endif</h1>
            <small class="text-muted">
                Created/Edit File Categories for Account File Storage.
            </small>
        </div>
    </div> <!-- .row end -->

@endsection
@section('content')
    <div class="row">
        <div class="offset-lg-3 col-lg-6 col-xs-12">
            <div class="card">
                <div class="card-body">

                    <form method="POST"
                          action="{{$category->id ? "/admin/file_categories/$category->id" : "/admin/file_categories"}}">
                        @method($category->id ? "PUT" : "POST")
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="name"
                                           {{$category->locked ? "disabled" : null}} value="{{$category->name}}">
                                    <label>Category Name:</label>
                                    <span class="helper-text">Enter category (i.e. Supporting Documentation)</span>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-lg-8">
                                <div class="form-floating">
                                    {!! Form::select('type', \App\Enums\Core\AccountFileType::getSelectable(), $category->type?->value, ['class' => 'form-control']) !!}
                                    <label>Uploaded File Type:</label>
                                    <span class="helper-text">Select the type of file allowed.</span>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-floating">
                                    {!! Form::select('default_public', [0 => 'No', 1 => 'Yes'], $category->default_public, ['class' => 'form-control']) !!}
                                    <label>Is file public by default?:</label>
                                    <span class="helper-text">If yes, this file will be accessible on the internet. (Use with caution!)</span>
                                </div>
                            </div>

                        </div>


                        <div class="row mt-3">
                            <div class="col-lg-6">
                                @if($category->id && !$category->locked)
                                    <a class="confirm text-danger"
                                       data-message="Are you sure you want to delete this category? Any files associated will be moved into the Unsorted folder on each account."
                                       data-method="DELETE" href="/admin/file_categories/{{$category->id}}"><i
                                            class="fa fa-trash"></i> Remove File Category</a>
                                @endif
                            </div>
                            <div class="col-lg-6">
                                <button type="submit" class="btn btn-primary ladda pull-right" data-style="zoom-out">
                                    <i class="fa fa-save"></i> Save Category
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
