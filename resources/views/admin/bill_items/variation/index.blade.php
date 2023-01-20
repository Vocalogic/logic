@extends('layouts.admin', ['title' => "$item->name Variations", 'crumbs' => $crumbs])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">{{$item->name}} Variations</h1>
            <small class="text-muted">{{$item->description ?: null}}</small>
        </div>
    </div> <!-- .row end -->
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-2">
            @include('admin.bill_items.menu')
        </div>
        <div class="col-xl-10">
            @if($item->parent)
                <div class="alert {{bma()}}info">
                    This item is a variation of
                    <a href="/admin/category/{{$item->parent->category->id}}/items/{{$item->parent->id}}">
                        {{$item->parent->name}}
                    </a>.
                    This item will not be shown directly in the shop but will be shown as a different variation of the
                    item to select and purchase.
                </div>
            @endif

            @include('admin.bill_items.variation.fields')
                @if(!$item->parent)
                    <a class="live mt-2 w-100 btn btn-{{bm()}}primary" data-title="Variation to {{$item->name}}"
                       href="/admin/category/{{$item->category->id}}/items/{{$item->id}}/variation/create">
                        <i class="fa fa-recycle"></i> Add Variation
                    </a>

                @endif
        </div>
    </div>

@endsection
