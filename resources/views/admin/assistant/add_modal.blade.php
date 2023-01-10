<div class="sModalArea">

    <form method="POST" action="/admin/cart/{{$uid}}/add/{{$type}}">
        @method("POST")
        @csrf
        <div class="row g-2">
            <div class="col-md-8 col-8">
                <div class="form-floating">
                    @if($type == 'product')
                    {!! Form::select('product_id', \App\Models\BillItem::productSelectable(), null, ['class' => 'form-select']) !!}
                    <label>Select Product</label>
                    <span class="helper-text">Select Product to Add</span>
                        @else
                        {!! Form::select('service_id', \App\Models\BillItem::serviceSelectable(), null, ['class' => 'form-select']) !!}
                        <label>Select Service</label>
                        <span class="helper-text">Select Service to Add</span>
                    @endif
                </div>
            </div>


            <div class="col-md-4 col-4">
                <div class="form-floating">
                    <input type="text" class="form-control" name="qty" value="1">
                    <label>QTY</label>
                    <span class="helper-text">Update Quantity.</span>
                </div>
            </div>

        </div>






        <div class="col-lg-6 mt-2">
            <input type="submit" name="submit" value="Add to Cart" class="btn btn-{{bm()}}primary wait"
                   data-anchor=".sModalArea">

        </div>
    </form>


</div>
