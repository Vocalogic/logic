<div class="row">
    <div class="col-lg-12">
        <form method="post" action="/admin/category/{{$category->id}}/items/{{$item->id}}/category">
            @csrf
            @method('POST')
            <div class="row mt-2">
                <div class="col-lg-12 col-md-12">
                    <div class="form-floating">
                        {!! Form::select('category_id', $item->category->getCategoriesByItem($item), $item->bill_category_id, ['class' => 'form-control']) !!}
                        <label>Select Category</label>
                        <span class="helper-text">Select the category to assign this item?</span>
                    </div>
                    <input type="submit" name="submit" value="Set Category" class="btn btn-primary">
                </div>
            </div>
        </form>
    </div>
</div>
