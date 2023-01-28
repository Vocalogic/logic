<div class="sModalArea">

    <form method="POST" action="/admin/quotes/{{$quote->id}}/items/{{$item->id}}">
        @method("PUT")
        @csrf
        <div class="row g-2">
            <div class="col-md-4 col-4">
                <div class="form-floating">
                    <input type="text" class="form-control" name="price" value="{{moneyFormat($item->price,2)}}">
                    <label>Price</label>
                    <span class="helper-text">Update Price</span>
                </div>
            </div>

            <div class="col-md-4 col-4">
                <div class="form-floating">
                    <input type="text" class="form-control" name="qty" value="{{$item->qty}}">
                    <label>QTY</label>
                    <span class="helper-text">Update Quantity.</span>
                </div>
            </div>
            @if($item->item && $item->item->type == 'services')
                <div class="col-md-4 col-4">
                    <div class="form-floating">
                        {!! Form::select('frequency', \App\Enums\Core\BillFrequency::getSelectable(), $item->frequency?->value ?: 'MONTHLY', ['class' => 'form-control']) !!}
                        <label>Billing Frequency</label>
                        <span class="helper-text">Select how often this service will be billed.</span>
                    </div>
                </div>
            @endif

        </div>
        <div class="row mt-2">
            <div class="col-md-12">
                <div class="form-floating">
                    <textarea class="form-control" style="height: 100px;" name="description">{{$item->description}}</textarea>
                    <label>Description</label>
                    <span class="helper-text">Enter base description of item.</span>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-12">
                <div class="form-floating">
                    <textarea class="form-control" name="notes" style="height:150px;">{{$item->notes}}</textarea>
                    <label>Notes</label>
                    <span class="helper-text">Enter additional notes on item (i.e. specifics)</span>
                </div>
            </div>
        </div>


        @if($item->item && $item->item->type == 'products')
            <div class="row mt-3 g-2">
                <div class="col-md-4 col-4">
                    <div class="form-floating">
                        {!! Form::select('frequency', array_replace(['' => '-- No Financing --'], \App\Enums\Core\BillFrequency::getSelectable()), $item->frequency?->value ?: '', ['class' => 'form-control']) !!}
                        <label>Financing Option</label>
                        <span class="helper-text">Select how often this service will be billed.</span>
                    </div>
                </div>
                <div class="col-md-4 col-4">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="payments"
                               value="{{$item->payments}}">
                        <label>Number of Payments</label>
                        <span class="helper-text">If financing, how many payments should total be split into.</span>
                    </div>
                </div>
                <div class="col-md-4 col-4">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="finance_charge"
                               value="{{$item->finance_charge ?: setting('quotes.defaultFinanceCharge')}}">
                        <label>Finance Charge (%)</label>
                        <span class="helper-text">If adding a finance charge, enter it here.</span>
                    </div>
                </div>
            </div>
        @endif


        <div class="col-lg-12 mt-2">
            <button type="submit" class="btn btn-primary ladda w-25 pull-right" data-style="zoom-out">
                   <i class="fa fa-save"></i> Save Item
            </button>

            <a class="confirm text-danger"
               data-message="Are you sure you want to remove {{$item->item->name}}?"
               href="/admin/quotes/{{$quote->id}}/del/{{$item->id}}"
               data-method="DELETE">
                <i class="fa fa-trash"></i> Remove {{$item->item->name}}</a>
        </div>
    </form>


</div>
