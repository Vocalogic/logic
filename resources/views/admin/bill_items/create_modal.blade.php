<p class="card-text">
    You are creating a new <b>{{\Illuminate\Support\Str::singular($type)}}</b> in {{$cat->name}}.
    Once you have created the initial item, and the default price, you will be able to add items for your
    shop, and additional marketing information.
</p>
<form method="POST" action="/admin/category/{{$cat->id}}/items" class="createForm">
    @method('POST')
    @csrf
<div class="card border-primary">
    <div class="card-body">
    <div class="row">
        <div class="row  mt-2">
            <x-form-input name="code" label="{{ucfirst($type)}} Code/SKU" icon="database">
                Enter a code/sku to define this item.
            </x-form-input>

            <x-form-input name="name" label="{{ucfirst($type)}} Name" icon="bars">
                Enter name to be used on invoice/quote/shop
            </x-form-input>

            <x-form-input name="price" label="Selling Price" icon="dollar">
                Enter the selling price for this item.
            </x-form-input>
        </div>

        <div class="row mt-3">
            <div class="offset-4 col-8">
                <input type="submit" class="w-100 btn btn-{{bm()}}primary wait"
                       data-anchor="createForm"
                       data-message="Creating Item.."
                       value="Save">
            </div>
        </div>
    </div>

    </div>
</div>


</form>
