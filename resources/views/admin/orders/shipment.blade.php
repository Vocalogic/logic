<p class="card-text">
    Here you will be able to select an existing shipment (if any exists) to apply this item to or create a new shipment.
</p>

<form method="post" action="/admin/orders/{{$order->id}}/items/{{$item->id}}/shipment">
    @csrf
    @method('POST')
    @if(\App\Models\Shipment::where('order_id', $order->id)->count())
        <h6 class="fw-bold">Select Existing Shipment</h6>
        <div class="row mt-2">
            <div class="col-lg-12 col-md-12">
                <div class="form-floating">
                    {!! Form::select('shipment_id', array_replace([0 => '-- Select Shipment --'], $order->shipmentSelectable()), $item->shipment ? $item->shipment->id : 0, ['class' => 'form-select']) !!}
                    <label>Reassign Shipment #</label>
                    <span class="helper-text">Select an existing order shipment to apply this item to.</span>
                </div>

            </div>
        </div>

    @endif

    <h6 class="fw-bold mt-2">Create new Shipment</h6>
    <div class="row mt-2">
        <div class="col-lg-12 col-md-12">
            <div class="form-floating">
                {!! Form::select('vendor_id', array_replace([0 => '-- Select Vendor --'], \App\Models\Vendor::pluck("name", "id")->all()), null, ['class' => 'form-select']) !!}
                <label>Select Vendor Assignment</label>
                <span class="helper-text">Which vendor are you sourcing this product?</span>
            </div>

        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <div class="form-floating">
                <input type="text" name="ship_company" class="form-control" value="{{$order->account->name}}">
                <label>Destination Company Name</label>
                <span class="helper-text">Enter the name of the company for shipment.</span>
            </div>
        </div>
    </div>


    <div class="row mt-2">
        <div class="col-12">
            <div class="form-floating">
                <input type="text" name="ship_contact" class="form-control" value="{{$order->account->admin->name}}">
                <label>Destination Contact</label>
                <span class="helper-text">Enter the primary contact for shipment.</span>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12">
            <div class="form-floating">
                <input type="text" name="ship_address" class="form-control" value="{{$order->account->address}}">
                <label>Destination Address</label>
                <span class="helper-text">Enter the destination address.</span>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12">
            <div class="form-floating">
                <input type="text" name="ship_address2" class="form-control" value="{{$order->account->address2}}">
                <label>Destination Address 2 (Suite/Building)</label>
                <span class="helper-text">Enter the Suite or Building Number (optional).</span>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12">
            <div class="form-floating">
                <input type="text" name="ship_csz" class="form-control" value="{{$order->account->csz}}">
                <label>City, State, Zip</label>
                <span class="helper-text">Enter the destination city, state and zipcode.</span>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12">
            <button type="submit" name="save" class="btn btn-primary ladda" data-style="expand-left"><i class="fa fa-save"></i> Create/Assign Shipment</button>
        </div>
    </div>

</form>
