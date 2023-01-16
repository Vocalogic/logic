<p class="card-text">
    You are creating a new <b>{{\Illuminate\Support\Str::singular($type)}}</b> in {{$cat->name}}. Enter the information
    below to start building your pricing and definitions.
</p>
<form method="POST" action="/admin/category/{{$cat->id}}/items" class="createForm">
    @method('POST')
    @csrf

    <div class="row">

        <div class="row  mt-2">
            <div class="col-md-4 col-4">
                <div class="form-floating">
                    <input type="text" class="form-control" name="code">
                    <label>{{ucfirst($type)}} Code</label>
                    <span
                        class="helper-text">Enter a code to define this item.</span>
                </div>
            </div>
            <div class="col-md-8 col-8">
                <div class="form-floating">
                    <input type="text" class="form-control" name="name">
                    <label>{{ucfirst($type)}} Name</label>
                    <span class="helper-text">Enter name to be used on invoice/quote.</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-4 col-4">
            <div class="form-floating">
                <input type="text" class="form-control" name="price">
                <label>Enter Selling Price</label>
                <span class="helper-text">Enter the selling price for this item.</span>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-xl-6">
            <input type="submit" class="w-100 btn btn-outline-primary wait"
                   data-anchor="createForm"
                   data-message="Creating Item.."
                   value="Save">
        </div>
    </div>

</form>
