@extends('layouts.admin', ['title' => "$item->name Definitions",
    'crumbs' => $crumbs,
    'docs' => "https://logic.readme.io/docs/product-specifications",
    'log' => $item->logLink
])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">{{$item->name}} Definitions</h1>
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
            @include('admin.bill_items.specs.fields')

            <div class="card mt-3">
                <div class="lchart" id="itemHistorical"
                     data-title="Item Selling Price over Company History"
                     data-height="300"
                     data-url="/admin/graph/MRR_ACCOUNT?fn=getBillItemPriceChart&item={{$item->id}}&seriesType=radialBar&months=4"
                     data-xtype="datetime"
                     data-type="area"
                     data-y="Price Sold History"
                     data-disable-toolbar="true"
                     data-wait="Getting Item Pricing Historical...">
                </div>
            </div>
        </div>
    </div>

@endsection
