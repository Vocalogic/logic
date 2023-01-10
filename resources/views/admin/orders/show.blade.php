@extends('layouts.admin', ['title' => "Order #$order->id", 'crumbs' => [
     '/admin/orders' => "Orders",
     "Order #$order->id"
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Order #{{$order->id}} ({{$order->account->name}})</h1>
            <small class="text-muted">{{$order->name}}</small>
        </div>

    </div> <!-- .row end -->

@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            @if(!$order->hasBeen(\App\Enums\Core\OrderStatus::Verified))
                <a class="confirm btn btn-{{bm()}}info" data-message="Are you sure you want to verify this order?"
                   data-method="GET"
                   href="/admin/orders/{{$order->id}}/verify"><i class="fa fa-refresh"></i> Verify Order</a>
            @endif


                @if($order->hasBeen(\App\Enums\Core\OrderStatus::Verified) && !$order->hasBeen(\App\Enums\Core\OrderStatus::InProgress))
                    <a class="confirm btn btn-{{bm()}}info" data-message="Set order to in progress (being shipped or provisioned)"
                       data-method="GET"
                       href="/admin/orders/{{$order->id}}/progress"><i class="fa fa-refresh"></i> Set Order to In Progress</a>

                @endif

            @if($order->invoice->balance > 0)
                    <div class="mt-3 alert alert-danger">WARNING: <a href="/admin/accounts/{{$order->account->id}}/invoices/{{$order->invoice->id}}">Invoice {{$order->invoice->id}}</a> has not been paid.
                    </div>
            @endif



        </div>

        <div class="col-lg-8 mt-2">
            @if($order->items()->where('product', 0)->count())
                <div class="card mb-3 fieldset border border-muted">
                    <span class="fieldset-tile text-muted bg-body">Service Orders</span>
                    <div class="card-body">
                        @include('admin.orders.services')
                    </div>
                </div>
            @endif
            @if($order->items()->where('product', 1)->count())
                <div class="card mt-3 fieldset border border-muted">
                    <span class="fieldset-tile text-muted bg-body">Product Orders</span>
                    <div class="card-body">
                        @include('admin.orders.products')
                    </div>
                </div>
            @endif


        </div>

        <div class="col-lg-4">
            @if($order->active)
                <a href="/admin/orders/{{$order->id}}/send"
                   class="confirm btn btn-{{bm()}}info mb-3"
                   data-message="Are you sure you want to send the order link to the customer? Once done, customer will be able to view and make updates where applicable."
                   data-method="GET"><i class="fa fa-mail-forward"></i> Send to Customer
                </a>
                <a
                    class="btn btn-{{bm()}}danger pull-right text-white confirm mb-3"
                    data-message="Are you sure you want to close this order?"
                    data-method="GET"
                    href="/admin/orders/{{$order->id}}/close"><i class="fa fa-close"></i> Close Order
                </a>

            @endif


            @livewire('admin.activity-component', ['order' => $order])
        </div>
    </div>
@endsection
