<form method="post" action="/admin/bill_categories/{{$type}}/{{$cat->id}}" class="editForm"
      enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <div class="row g-3 mb-4">
        <div class="col-lg-6 col-md-12">
            <div class="form-floating">
                <input type="text" class="form-control" name="name" value="{{$cat->name}}">
                <label>Category Name</label>
                <span class="helper-text">Enter the category name</span>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="form-floating">
                <textarea class="form-control" rows=4 name="description">{{$cat->description}}</textarea>
                <label>Description</label>
                <span class="helper-text">Enter a short description (optional)</span>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-lg-6 col-md-12">
            <div class="form-floating">
                <input type="text" class="form-control" name="shop_name" value="{{$cat->shop_name}}">
                <label>Category Name for Shop</label>
                <span class="helper-text">How do we show this category in the shop?</span>
            </div>


        </div>

        <div class="col-lg-6 col-md-12">
            <div class="form-floating">
                {!! Form::select('shop_show', [1 => 'Yes', 0 => 'No'], $cat->shop_show, ['class' => 'form-select']) !!}
                <label>Show category in shop?</label>
                <span class="helper-text">Should this category be listed in the customer shop?</span>
            </div>
        </div>


    </div>

    <div class="row mb-3">
        <div class="col-lg-6">
            <div class="form-floating">
                <input type="file" name="shop_offer_image_id" class="drop"
                       data-default-file="{{$cat->shop_offer_image_id ? _file($cat->shop_offer_image_id)->relative : null}}"/>
                <label>Offer Banner Image</label>
                <span class="helper-text">If providing an offer, upload 1200x500 PNG</span>
            </div>

            <div class="form-floating mt-2">
                <input type="file" name="photo_id" class="drop"
                       data-default-file="{{$cat->photo_id ? _file($cat->photo_id)->relative : null}}"/>
                <label>Category Image (for Shop)</label>
                <span class="helper-text">For the landing page upload a category photo (130x130)</span>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-floating">
                <textarea class="form-control" style="height: 150px;" name="shop_offer">{{$cat->shop_offer}}</textarea>
                <label>Shop Offer Details</label>
                <span class="helper-text">Enter offer text when displaying this category</span>
            </div>
        </div>
    </div>

    <div class="row mb-3">

        <div class="col-lg-12 col-md-12">
            <button type="submit" class="btn btn-primary pull-right ladda">
                <i class="fa fa-save"></i> Save Category
            </button>
        </div>
    </div>

</form>
<script>
    $('.drop').dropify();
</script>
