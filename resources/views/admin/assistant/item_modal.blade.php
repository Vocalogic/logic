<div class="sModalArea">

    <form method="POST" action="/admin/cart/{{$uid}}/item/{{$iid}}">
        @method("POST")
        @csrf
        <div class="row g-2">
            <div class="col-md-4 col-4">
                <div class="form-floating">
                    <input type="text" class="form-control" name="price" value="{{moneyFormat($item->price)}}">
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





        <div class="col-lg-12 mt-2">

            <a class="btn btn-sm btn-outline-danger confirm" href="/admin/cart/{{$uid}}/item/{{$iid}}"
               data-method="DELETE"
               data-message="Are you sure you want to remove this item?"><i class="fa fa-trash"></i> Remove Item from Cart</a>
            <button type="submit" name="submit" class="btn btn-primary pull-right btn-xs ladda" data-style="expand-left">
                <i class="fa fa-edit"></i> Update Item</button>

        </div>
    </form>


</div>
