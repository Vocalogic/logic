@extends('layouts.admin', ['title' => "Shipment Order $", 'crumbs' => [
     '/admin/shipments' => "Shipments",
     "Shipment #$shipment->id"

]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Shipment #{{$shipment->id}} (Order #{{$shipment->order->id}})</h1>
            <small class="text-muted">Track Shipments and Tracking Status</small>
        </div>

    </div> <!-- .row end -->

@endsection
@section('content')
    <div class="row">
        <div class="col-lg-4">
            @include('admin.shipments.shipping')
        </div>
        <div class="col-lg-8 rightcol">
            @if($shipment->status != \App\Enums\Core\ShipmentStatus::Draft)
                @include('admin.shipments.tracking')
            @endif


            @include('admin.shipments.items')
            <div class="mt-3">
                @if($shipment->status == \App\Enums\Core\ShipmentStatus::Draft && $shipment->vendor)
                    <div role="alert" class="alert {{currentMode() == 'dark' ? 'bg-light-danger' : 'alert-danger'}}">
                        <strong>NOTE:</strong> This order has not been submitted to {{$shipment->vendor->name}}. You can make
                        changes to this order while in a draft.
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
