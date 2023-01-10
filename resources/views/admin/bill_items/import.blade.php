<p class="card-text">You are about to import <strong>{{$item->name}}</strong>.

    @if($item->product)
        This item is a product and will be added to one of your one-time product categories selected below.
    @else
        This item is a monthly recurring service and will be added to the service category selected below.
@endif

<form method="POST" action="/admin/import/{{$item->lid}}">
    @csrf
    @method('POST')
    <div class="row mt-3">
        <div class="col-lg-12">

            @if($item->product)
                <div class="form-floating">
                    {!! Form::select('category_id', \App\Models\BillCategory::where('type', 'products')->pluck("name", "id")->all(), null, ['class' => 'form-control']) !!}
                    <label>Select Product Category</label>
                    <span class="helper-text">Select the product category for this item. </span>
                </div>
            @else

                <div class="form-floating">
                    {!! Form::select('category_id', \App\Models\BillCategory::where('type', 'services')->pluck("name", "id")->all(), null, ['class' => 'form-control']) !!}
                    <label>Select Service Category</label>
                    <span class="helper-text">Select the service category for this item. </span>
                </div>
            @endif
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-lg-6">
            <div class="form-floating">
                <input type="text" class="form-control" name="price" value="{{number_format($item->msrp,2) ?: 0.00}}">
                <label>Default Price:</label>
                <span class="helper-text">Enter the default price you wish to sell this item (optional)</span>
            </div>
        </div>


    </div>

    <div class="row mt-3">
        <div class="col-lg-6">
            <input class="btn btn-{{bm()}}primary wait" type="submit" name="submit" value="Import {{$item->name}}">
        </div>
    </div>
</form>

