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
                <a class="btn btn-outline-primary" data-bs-toggle="modal" href="#newCategory"
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

    <x-modal name="newCategory" title="Create {{ucfirst($type)}} Category" size="lg">
        <form method="post" action="/admin/bill_categories/{{$type}}">
            @method('POST')
            @csrf
            <div class="row">
                <div class="col-12">
                    <p>
                        Categories are used to categorize your products and services. They are also used for your shop
                        to assign tags where customers can filter their searches based on the category. Products and
                        Services in your categories should be related and make searching easy for your customers.
                    </p>
                </div>
            </div>
            <div class="card border-primary">
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <x-form-input name="name" icon="link" label="{{ucfirst($type)}} Category Name">
                            Enter the category name
                        </x-form-input>

                        <x-form-input name="description" icon="comments" label="Category Description">
                            Enter a short description
                        </x-form-input>

                        <div class="offset-4 col-8">
                            <input type="submit" class="w-100 btn btn-primary wait" data-message="Saving Category.."
                                   value="Save Category">
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </x-modal>

@endsection
