@extends('layouts.admin', ['title' => $item->name, 'crumbs' => [
     $item->name,
]])

@section('content')

    <div class="row g-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row row-deck">
                        <div class="col-lg-4 col-md-12">
                            <div class="row">
                                <div class="col-12">
                                    <img src="data:image/jpeg;base64,{{$item->logo_id}}" alt="{{$item->name}}"
                                         class="img-fluid">
                                </div>
                                <div class="col-4 mt-3">
                                    @if($item->photo_1)
                                        <img src="data:image/jpeg;base64,{{$item->photo_1}}" alt="{{$item->name}}"
                                             class="img-fluid">
                                    @endif
                                </div>
                                <div class="col-4 mt-3">
                                    @if($item->photo_2)
                                        <img src="data:image/jpeg;base64,{{$item->photo_2}}" alt="{{$item->name}}"
                                             class="img-fluid">
                                    @endif
                                </div>
                                <div class="col-4 mt-3">
                                    @if($item->photo_3)
                                        <img src="data:image/jpeg;base64,{{$item->photo_3}}" alt="{{$item->name}}"
                                             class="img-fluid">
                                    @endif
                                </div>


                            </div>
                        </div>
                        <div class="col-lg-8 col-md-12">
                            <div>
                                <h4 class="mt-4 mt-lg-0"><strong>{{$item->name}}</strong></h4>
                                <div class="my-3">
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star text-warning"></i>
                                    <span class="text-muted ms-3">(49 reviews)</span>
                                </div>

                                <p class="my-4">
                                    {!! $item->full !!}
                                    @if($item->slick_id)
                                        <span class="badge bg-success"><i class="fa fa-check"></i> Includes Sales Slick</span>
                                    @else
                                        <span class="badge bg-danger"><i class="fa fa-times"></i> No Sales Slick Included</span>
                                    @endif
                                </p>
                                <div class="row">
                                    <div class="col-lg-6 col-xl-6 col-sm-12 col-md-12">
                                        <h6 class="card-title">{{$item->feature_headline}}</h6>
                                        <ul>
                                            @foreach($item->bullets as $bullet)
                                                <li>{!! $bullet !!} </li>
                                            @endforeach
                                        </ul>
                                    </div>


                                </div>

                                <div class="d-flex">
                                    <a class="btn btn-primary mx-2 live" data-title="Import {{$item->name}}" href="/admin/import/{{$item->lid}}"><i
                                            class="fa fa-download me-1"></i> Import to Product Catalog
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- .row end -->
@endsection
