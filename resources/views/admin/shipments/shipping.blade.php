<div class="card">
    <div class="card-body">
        <p class="card-title">Shipping Information</p>
        <p class="mb-3">Enter the shipping information below that will be sent to the vendor. Be sure to include any
        notes for shipping if required.</p>

        <form method="POST" action="/admin/shipments/{{$shipment->id}}">
            @csrf
            @method('PUT')

            <div class="row g-3 mb-4">
                <div class="col-lg-12 col-md-12">

                    <div class="form-floating mb-3">
                        {!! Form::select('vendor_id', \App\Models\Vendor::all()->pluck("name", "id")->all(), $shipment->vendor_id, ['class' => 'form-control']) !!}
                        <label>Select Vendor:</label>
                        <span class="helper-text">Select the vendor to submit this order to.</span>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="ship_company" value="{{$shipment->ship_company ?: $shipment->order->account->name}}">
                        <label>Company Name:</label>
                        <span class="helper-text">Enter the name of the company receiving shipment.</span>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="ship_contact" value="{{$shipment->ship_contact ?: $shipment->order->account->admin->name}}">
                        <label>Receiving Contact Name:</label>
                        <span class="helper-text">Enter the name of the person receiving shipment.</span>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="ship_address" value="{{$shipment->ship_address ?: $shipment->order->account->address}}">
                        <label>Address Line 1:</label>
                        <span class="helper-text">Enter the shipping address</span>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="ship_address2" value="{{$shipment->ship_address2 ?: $shipment->order->account->address2}}">
                        <label>Address Line 2:</label>
                        <span class="helper-text">Enter the Suite or Unit Number (optional)</span>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="ship_csz" value="{{$shipment->ship_csz ?: $shipment->order->account->csz}}">
                        <label>City, State and Zip:</label>
                        <span class="helper-text">Enter the City, State and Zip</span>
                    </div>

                    <div class="form-floating mb-3">
                        <textarea class="form-control" name="ship_notes" rows="30">{{$shipment->ship_notes}}</textarea>
                        <label>Notes to Vendor/Shipping:</label>
                    </div>
                    <input type="submit" class="btn btn-{{bm()}}primary" value="Update Shipping Info">

                    <a class="btn btn-{{bm()}}danger confirm pull-right"
                       data-message="Are you sure you want to cancel this hardware order?"
                       data-method="DELETE"
                       href="/admin/shipments/{{$shipment->id}}">
                        <i class="fa fa-trash"></i> Cancel Order
                    </a>


                </div>
            </div>


        </form>

    </div>
</div>
