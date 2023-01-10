@extends('layouts.admin', ['title' => 'Select Item', 'crumbs' => [
     "Select Items",
]])

@section('content')
    <div class="row row-deck">
        @foreach($items as $item)
            <div class="col-xl-3 col-xxl-3 col-lg-4 col-md-4 col-sm-6 mb-3">
                <div class="card text-center overflow-hidden">
                    <div class="card-body py-4">
                        <img src="data:image/jpeg;base64,{{$item->logo_id}}" alt="{{$item->name}}"
                             class="avatar xl shadow img-thumbnail">
                    </div>
                    <div class="card-footer border-0">
                        <h6>{{$item->name}}</h6>
                        <span class="color-400">{{$item->short}}</span>
                    </div>
                    <div class="card-footer">
                        <a class="btn btn-block btn-{{bm()}}primary"
                           href="/admin/market/{{$industry}}/{{$category}}/{{$item->lid}}"><i class="fa fa-plus"></i>
                            View {{$item->name}}</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endsection
