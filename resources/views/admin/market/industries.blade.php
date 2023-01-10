@extends('layouts.admin', ['title' => 'Select Industry', 'crumbs' => [
     "Select Industry",
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Select your Industry</h1>
            <small class="text-muted">Set your industry for quick access to products and services you can support.</small>
        </div>
    </div> <!-- .row end -->

@endsection
@section('content')
    <div class="row row-deck">
        @foreach($industries as $industry)
            <div class="col-xl-3 col-xxl-3 col-lg-4 col-md-4 col-sm-6 mb-3">
                <div class="card text-center overflow-hidden">
                    <div class="card-body py-4">
                        <img src="data:image/jpeg;base64,{{$industry->image}}" alt="{{$industry->name}}" class="rounded-circle avatar xl shadow img-thumbnail">
                    </div>
                    <div class="card-footer border-0">
                        <h6>{{$industry->name}}</h6>
                        <span class="color-400">{{$industry->description}}</span>
                    </div>
                    <div class="card-footer">
                        <a class="btn btn-block btn-{{bm()}}primary" href="/admin/market/set/{{$industry->slug}}"><i class="fa fa-plus"></i> Set Industry</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
