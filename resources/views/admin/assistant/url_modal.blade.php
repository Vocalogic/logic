<p>
    Direct your customer to a specific product or service.

</p>

<form method="post" action="/admin/cart/{{$uid}}/command/url">
    @method('POST')
    @csrf
    <div class="row mb-3">
        <label for="product" class="col-sm-4 col-form-label">Select Product</label>
        <div class="col-sm-8">
            {!! Form::select('product_id', \App\Models\BillItem::productSelectable(), null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="row mb-3">
        <label for="service" class="col-sm-4 col-form-label">Select Service</label>
        <div class="col-sm-8">
            {!! Form::select('service_id', \App\Models\BillItem::serviceSelectable(), null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="row">
        <input type="submit" class="btn btn-primary" value="Redirect Customer">
    </div>
</form>
