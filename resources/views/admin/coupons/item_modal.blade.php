<form method="post" action="/admin/coupons/{{$coupon->id}}/items/{{$item->id}}">
    @method('PUT')
    @csrf
    <div class="row mt-2">
        <div class="col-lg-12 col-md-12">
            <div class="form-floating">
                {!! Form::select('bill_item_id', \App\Models\BillItem::selectable(), $item->bill_item_id, ['class' => 'form-select', 'id' => 'selectmodal']) !!}
                <label>Select Product/Service</label>
                <span class="helper-text">Select a product or service that this coupon will work for</span>
            </div>
        </div>
    </div>

    <div class="row mt-2">

        <div class="col-lg-6 col-md-6">
            <div class="form-floating">
                <input type="text" name="min_qty" value="{{$item->min_qty}}" class="form-control">
                <label>Min Qty Required</label>
                <span class="helper-text">Enter a minimum quantity</span>
            </div>
        </div>

        <div class="col-lg-6 col-md-6">
            <div class="form-floating">
                <input type="text" name="max_qty" value="{{$item->max_qty}}" class="form-control">
                <label>Max Qty Allowed</label>
                <span class="helper-text">Enter a maxmium quantity</span>
            </div>
        </div>

    </div>

    <div class="row mt-4">
        <div class="col-lg-6">
            <a class="text-danger confirm"
               data-method="DELETE"
               data-message="Are you sure you want to remove this item from this coupon?"
               href="/admin/coupons/{{$coupon->id}}/items/{{$item->id}}">
                <i class="fa fa-trash"></i> Remove Item
            </a>
        </div>
        <div class="col-lg-6">
            <button type="submit" class="btn btn-primary ladda pull-right" data-style="zoom-out">
                <i class="fa fa-save"></i> Save Requirements
            </button>
        </div>
    </div>


</form>
