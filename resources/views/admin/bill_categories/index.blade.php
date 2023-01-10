@extends('layouts.admin', ['title' => "Billing Categories ({$type})", 'crumbs' => [
     ucfirst($type)." Categories",
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">{{ucfirst($type)}} Categories</h1>
            <small class="text-muted">Create and update your {{$type}} categories.</small>
        </div>
    </div> <!-- .row end -->

@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="input-group">
                <a class="btn btn-outline-secondary" data-bs-toggle="modal" href="#newCategory"
                   type="button"><i class="fa fa-plus"></i> New {{ucfirst($type)}} Category
                </a>
                <input type="text" class="form-control" placeholder="Search...">
                <button class="btn btn-secondary" type="button">Search</button>
            </div>
        </div>

        <div class="col-lg-12">
            @include('admin.bill_categories.list')
        </div>
    </div>

    <div class="modal fade" id="newCategory" tabindex="-1" role="dialog" aria-labelledby="liveModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create new Category</h5>
                </div>
                <div class="modal-body">
                    <form method="post" action="/admin/bill_categories/{{$type}}">
                        @method('POST')
                        @csrf
                        <div class="row g-3 mb-4">
                            <div class="col-lg-6 col-md-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="name" value="">
                                    <label>Category Name</label>
                                    <span class="helper-text">Enter the category name</span>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="form-floating">
                                    <textarea class="form-control" rows=4 name="description"></textarea>
                                    <label>Description</label>
                                    <span class="helper-text">Enter a short description (optional)</span>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <input type="submit" class="btn btn-primary rounded wait" data-message="Saving Category.." value="Save">
                            </div>


                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection
