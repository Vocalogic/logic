<div class="card mb-3">
    <div class="card-body">
        <p class="card-title">Tracking Information</p>
        <p>
            When you have received tracking information you can apply it here. You can also select if you wish
            to have a notification sent to your customer with the tracking information.
        </p>
        <form method="POST" action="/admin/shipments/{{$shipment->id}}/tracking" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="tracking" value="{{$shipment->tracking}}">
                        <label>Tracking Number:</label>
                        <span class="helper-text">Enter tracking number for the shipment.</span>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-floating mb-3">
                        {!! Form::select('email_customer', [0 => 'No', 1 => 'Yes'], 0, ['class' => 'form-control']) !!}
                        <label>Re/Email Customer?:</label>
                        <span class="helper-text">Send customer tracking information?</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="vendor_sub" value="{{$shipment->vendor_sub}}">
                        <label>Vendor Subtotal:</label>
                        <span class="helper-text">Enter the price before shipping/taxes.</span>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="vendor_shipping" value="{{$shipment->vendor_shipping}}">
                        <label>Vendor Shipping:</label>
                        <span class="helper-text">Enter the price of shipping.</span>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="vendor_total" value="{{$shipment->vendor_total}}">
                        <label>Vendor Total:</label>
                        <span class="helper-text">Enter the total of the vendor invoice.</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-floating">
                        <input type="file" name="vendor_invoice" class="drop"
                               data-default-file="{{$shipment->vendor_invoice ? _file($shipment->vendor_invoice)->relative : null}}"/>
                        <label>Upload Vendor Invoice</label>
                    </div>
                </div>
                @if($shipment->tracking)
                    <div class="col-lg-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="expected_arrival"
                                   value="{{$shipment->expected_arrival?->format("m/d/Y")}}">
                            <label>Expected Arrival Date:</label>
                            <span
                                class="helper-text">Enter the arrival date expected from the tracking information.</span>
                        </div>
                    </div>
                @endif
            </div>

            <div class="row mt-3">
                <div class="col-lg-12">
                    <input type="submit" name="submit" value="Update/Send Tracking Information" class="btn btn-primary">
                    @if($shipment->tracking)
                        <a href="/admin/shipments/{{$shipment->id}}/close" class="btn btn-outline-danger confirm"
                           data-method="GET" data-message="Are you sure you want to close this order?"><i class="fa fa-mail-forward"></i> Close Order</a>
                    @endif
                    @if($shipment->vendor_invoice)
                        <a href="{{_file($shipment->vendor_invoice)->relative}}" class="btn btn-info"><i class="fa fa-download"></i> Download Vendor Invoice</a>
                    @endif
                </div>
            </div>


        </form>
    </div>
</div>
