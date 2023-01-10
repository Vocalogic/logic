@extends('layouts.admin', ['title' => 'Select Category', 'crumbs' => [
     "Select Category",
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Select Category of Interest</h1>
            <small class="text-muted">Select a category for the service or product you wish to offer.</small>
        </div>
        <div class="col-auto">
            <a class="btn btn-{{bm()}}primary" href="/admin/market/clear"><i class="fa fa-refresh"></i> Reset Industry</a>
        </div>
    </div> <!-- .row end -->

@endsection
@section('content')
    <div class="row row-deck">
        @foreach($cats as $cat)
            <div class="col-xl-3 col-xxl-3 col-lg-4 col-md-4 col-sm-6 mb-3">
                <div class="card text-center overflow-hidden">
                    <div class="card-body py-4">
                        <img src="data:image/jpeg;base64,{{$cat->image}}" alt="{{$cat->name}}" class="rounded-circle avatar xl shadow img-thumbnail">
                    </div>
                    <div class="card-footer border-0">
                        <h6>{{$cat->name}}</h6>
                        <span class="color-400">{{$cat->description}}</span>
                    </div>
                    <div class="card-footer">
                        <a class="btn btn-block btn-{{bm()}}primary" href="/admin/market/{{$industry}}/{{$cat->slug}}"><i class="fa fa-plus"></i> View {{$cat->name}} Products</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
