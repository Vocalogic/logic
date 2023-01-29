<div class="newVariant">
    <p>
        You are about to create a variation of <code>{{$item->name}}</code>. This item will not be selectable directly
        from your product catalog or in the shop. Once you select the primary item, you will have the option to select a
        variation and then be allowed to select the variation you create here.
    </p>
    <form method="POST" action="/admin/category/{{$category->id}}/items/{{$item->id}}/variation">
        @csrf
        @method('POST')
        <div class="row mb-3">
            <div class="col-lg-4">
                <div class="form-floating">
                    <input type="text" class="form-control" name="code" value="{{$item->code}}-VARIATION">
                    <label>Code/SKU</label>
                    <span class="helper-text">New Item Code</span>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="form-floating">
                    <input type="text" class="form-control" name="name" value="{{$item->name}} Variation">
                    <label>New Variant Product Name</label>
                    <span class="helper-text">Enter the product catalog name for this variant.</span>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-9 col-sm-8">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" checked role="switch" value="1" id="photos"
                           name="copy_photos">
                    <label class="form-check-label" for="photos">Copy photo(s) into new variation?</label>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-9 col-sm-8">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" checked role="switch" value="1" id="tags"
                           name="copy_tags">
                    <label class="form-check-label" for="tags">Copy all tags?</label>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-9 col-sm-8">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" checked role="switch" value="1" id="addons"
                           name="copy_addons">
                    <label class="form-check-label" for="addons">Copy addons?</label>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-9 col-sm-8">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" checked role="switch" value="1" id="requirements"
                           name="copy_requirements">
                    <label class="form-check-label" for="requirements">Copy Data Requirements?</label>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-lg-12">
                <button type="submit" class="btn btn-primary ladda pull-right" data-style="zoom-out">
                    <i class="fa fa-save"></i> Create Variation
                </button>
            </div>
        </div>

    </form>
</div>
