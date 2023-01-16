<form method="post" action="{{$option->id ? "/admin/category/$cat->id/items/$item->id/addons/$addon->id/options/$option->id" : "/admin/category/$cat->id/items/$item->id/addons/$addon->id/options/"}}">
    @method($option->id ? 'PUT' : 'POST')
    @csrf
    <h6 class="fw-bold">Addon Option</h6>
    <p class="card-text">
        Add an option for a user to select when adding this addon. This can be a product/service or a custom name.
        <b>*NOTE*</b> Custom names will only reflect income and not expenses. If you wish to track expenses you must
        create and link an item here.
    </p>

    <div class="row mt-2">
        <div class="col-lg-6 col-md-12">
            <div class="form-floating">
                <input type="text" class="form-control" name="name" value="{{$option->name}}">
                <label>Custom Name (opt.)</label>
                <span class="helper-text">If not selecting a product or service, enter the name here.</span>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="form-floating">
                {!! Form::select('bill_item_id', \App\Models\BillItem::selectable($item), $option->bill_item_id, ['class' => 'form-select']) !!}
                <label>Select Product/Service (opt)</label>
                <span class="helper-text">Select the product or service to set for this option.</span>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-lg-6 col-md-12">
            <div class="form-floating">
                <input type="text" class="form-control" name="price" value="{{moneyFormat($option->price)}}">
                <label>Price (blank for default)</label>
                <span class="helper-text">Enter the discount price for bundling.</span>
            </div>
            <div class="form-floating mt-2">
                <input type="text" class="form-control" name="max" value="{{$option->max ?: 1}}">
                <label>Max Quantity</label>
                <span class="helper-text">How many can be purchased in this bundle option?</span>
            </div>
        </div>

        <div class="col-lg-6 col-md-12">
            <div class="form-floating">
                <textarea class="form-control" name="notes" style="height:100px;">{!! $option->notes !!}</textarea>
                <label>Notes (restrictions, etc)</label>
                <span class="helper-text">Enter any notes for when someone selects this option.</span>
            </div>
        </div>

    </div>

    <div class="row mt-2">
        <div class="col-lg-6">
            <input type="submit" class="btn btn-{{bm()}}primary" value="Save Option">
        </div>
        @if($option->id)
            <div class="col-lg-6">
                <a class="btn btn-{{bm()}}danger confirm"
                   data-method="DELETE"
                   data-message="Are you sure you want to remove this option?"
                   href="/admin/category/{{$cat->id}}/items/{{$item->id}}/addons/{{$addon->id}}/options/{{$option->id}}"><i class="fa fa-trash"></i> Remove Option</a>
            </div>
            @endif
    </div>

</form>
