<div class="row">
    <div class="col-lg-12">
        <form method="post" action="/admin/accounts/{{$account->id}}/items/{{$item->id}}/addons">
            @csrf
            @method('POST')
            @foreach($item->item->addons as $addon)
                <div class="row">
                    <div class="col-6">
                        <div class="form-floating mt-2">
                            {{Form::select("add_$addon->id", $addon->selectable(), $addon->getServiceSelected($item, 'id'), ['class' => 'form-select'])}}
                            <label>{{$addon->name}}</label>
                            <span class="helper-text">{{$addon->description}}</span>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-floating mt-2">
                            <input type="text" name="price_{{$addon->id}}" class="form-control" value="{{$addon->getServiceSelected($item, 'price')}}">
                            <label>Price</label>
                            <span class="helper-text">Enter price override</span>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-floating mt-2">
                            <input type="text" name="qty_{{$addon->id}}" class="form-control" value="{{$addon->getServiceSelected($item, 'qty')}}">
                            <label>QTY</label>
                            <span class="helper-text">Enter qty</span>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="mt-2">
                <input type="submit" value="Save" class="btn btn-{{bm()}}primary">
            </div>
        </form>
    </div>
</div>
