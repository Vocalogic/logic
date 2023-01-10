@if($shipment->vendor)
    <div class="card mb-3">
        <div class="card-body">
            @if($shipment->status == \App\Enums\Core\ShipmentStatus::Submitted)
                <div class="alert alert-warning">This order has already been submitted. If you need to make changes
                    and resubmit you can, but you should inform the vendor that it's an update even though your order
                    number
                    will not change.
                </div>
            @endif

            <p class="card-title">Order Items</p>
            <p class="mb-2">
                The following items are to be ordered from <strong>{{$shipment->vendor->name}}</strong>. When the order is
                ready
                you can choose to either download the order and send to the vendor, or click <code>Submit Order to
                    Vendor</code>.
            </p>
        </div>
    </div>
    <table class="table custom-table">
        <thead>
        <tr>
            <th>Product</th>
            <th>Quantity</th>
        </tr>
        </thead>
        <tbody>
        @foreach($shipment->items as $item)
            <tr>
                <td>{{$item->name}} <a href="/admin/shipments/{{$shipment->id}}/del/{{$item->id}}"><i
                                class="fa fa-trash"></i></a></td>
                <td>
                    <a class="xedit" data-pk="{{$item->id}}" data-field="qty"
                       data-url="/admin/shipments/{{$shipment->id}}/live/{{$item->id}}"> {{$item->qty}}
                    </a>
                </td>

            </tr>
        @endforeach
        </tbody>
    </table>
    <a data-bs-toggle="modal" href="#oneModal" class="btn btn-outline-primary"><i class="fa fa-plus"></i> Add
        Product</a>
    <a class="btn btn-outline-info wait" data-anchor=".rightcol" href="/admin/shipments/{{$shipment->id}}/download"><i
                class="fa fa-cloud-download"></i> Download Order</a>
    <a class="confirm btn btn-outline-success" data-method="GET"
       data-message="Are you sure you want to submit this order to {{$shipment->vendor->name}}?"
       data-method="POST" href="/admin/shipments/{{$shipment->id}}/submit"><i class="fa fa-arrow-circle-right"></i>
        Submit to {{$shipment->vendor->name}}</a>



    <div class="modal fade" id="oneModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-vertical modal-dialog-scrollable">
            <div class="modal-content">

                <div class="px-xl-4 modal-header">
                    <h5 class="modal-title">Add Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body custom_scroll">

                    <table class="table datatable">
                        <thead>
                        <tr>
                            <th>Item</th>
                            <th>Expense</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(\App\Models\BillCategory::where('type', \App\Enums\Core\BillItemType::PRODUCT)->get() as $cat)

                            @foreach($cat->items()->where('is_shipped', true)->get() as $item)
                                <tr>
                                    <td>
                                        <a href="/admin/shipments/{{$shipment->id}}/add/{{$item->id}}">[{{$item->code}}]
                                            {{$item->name}}</a><br/><small
                                                class="text-muted">{{$item->category->name}}</small></td>
                                    <td>${{moneyFormat($item->ex_capex)}}</td>
                                </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@else
    <div class="card">
        <div class="card-body">
            <p>No vendor has been selected. Please select a vendor on the left to begin building your order.</p>
        </div>
    </div>

@endif
