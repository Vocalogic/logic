<form class='formAnchor' method="POST" action="/admin/invoices/{{$invoice->id}}/item/{{$item->id}}">
    @method('PUT')
    @csrf

    <div class="row mb-2">
        <div class="col-lg-6">
            @if(!$item->item)
                <div class="form-floating">
                    <input type="text" class="form-control" name="name" value="{{$item->name}}">
                    <label>Item Name</label>
                    <span class="helper-text">Enter a short name for this item</span>
                </div>
            @else
                <div class="form-floating">
                    <input disabled type="text" class="form-control" name="name" value="{{$item->name}}">
                    <label>Item Name</label>
                    <span class="helper-text">Enter a short name for this item</span>
                </div>
            @endif

        </div>
        <div class="col-lg-3">
            <div class="form-floating">
                <input type="text" class="form-control" name="price" value="{{moneyFormat($item->price)}}">
                <label>Price ($)</label>
                <span class="helper-text">Enter price per qty</span>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="form-floating">
                <input type="text" class="form-control" name="qty" value="{{$item->qty}}">
                <label>QTY</label>
                <span class="helper-text">Enter quantity</span>
            </div>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-lg-12">
            <div class="form-floating">
                <textarea style="height:200px;" class="form-control" name="description">{{$item->description}}</textarea>
                <label>Description</label>
                <span class="helper-text">Update description for product/service.</span>
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <input type="submit" name="save" value="Save" data-anchor='.formAnchor' class="wait btn btn-{{bm()}}primary">
    </div>


</form>
