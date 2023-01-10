<div class="row">
    <div class="col-lg-6">
        <div class="card mt-3 mb-3">
            <div class="card-body">
                <h6 class="card-title">Product Reservation</h6>
                <p>
                    Products that require additional contracts for larger purchases can be "reserved" with a lower
                    payment with limitations placed. For instance, if you are selling office space, automobiles, etc,
                    this option will enable customers to purchase a reservation to fulfill a larger purchase. Let's say
                    you are selling office space, and an office costs $1000/mo. You could offer a reservation price that
                    deducts the reservation off their first month's bill for $50.
                </p>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="row mt-3">
            <div class="col-lg-6">
                <div class="form-floating">
                    {!! Form::select('reservation_mode', [0 => 'Disabled', 1 => 'Enabled'], $item->reservation_mode, ['class' => 'form-control']) !!}
                    <label>Reservation Mode</label>
                    <span class="helper-text">Enable reservation mode for this item (Yes will NOT purchase the item in full).</span>
                </div>
            </div>

            <div class="col-lg-6 ">
                <div class="form-floating">
                    <input type="text" class="form-control" name="reservation_price"
                           value="{{moneyFormat($item->reservation_price)}}">
                    <label>Reservation Price</label>
                    <span class="helper-text">Enter the price to reserve this item.</span>
                </div>
            </div>
        </div>
        <div class="row mt-2">

            <div class="col-lg-12">
                <div class="form-floating">
                    <textarea class="form-control" name="reservation_details" style="height: 100px;">{{$item->reservation_details}}</textarea>
                    <label>Reservation Details</label>
                    <span class="helper-text">Explain what purchasing this reservation entails.</span>
                </div>
            </div>

            <div class="col-lg-12 mt-2">
                <div class="form-floating">
                    <input type="text" class="form-control" name="reservation_time"
                           value="{{$item->reservation_time}}">
                    <label>Length of Reservation</label>
                    <span class="helper-text">Enter how long this reservation will last if purchased (i.e. 30 days, etc)</span>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="form-floating">
                    <textarea class="form-control" name="reservation_refund" style="height: 100px;">{{$item->reservation_refund}}</textarea>
                    <label>Reservation Refund Details</label>
                    <span class="helper-text">Explain if a refund on a reservation is allowed, etc.</span>
                </div>
            </div>


        </div>

    </div>

</div>
