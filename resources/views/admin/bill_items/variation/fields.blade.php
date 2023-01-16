<div class="row">
    <div class="col-lg-12">
        <div class="card mt-3 mb-3">
            <div class="card-body">
                <h6 class="card-title">Variations</h6>
                <p>
                    Variations allow you to create "sub-items", that are the same product but have a different
                    name. Think of this like different packages of the same item; for things like license packs
                    or similar products and services just with one thing changed.
                </p>

                <form method="POST" action="/admin/category/{{$cat->id}}/items/{{$item->id}}/variation">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        @if(!$item->parent_id)
                            <div class="col-md-6 col-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="variation_category"
                                           value="{{$item->variation_category}}">
                                    <label>Variation Category (opt)</label>
                                    <span class="helper-text">If providing variations what is the category (i.e. Number of Licenses)</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="variation_name"
                                           value="{{$item->variation_name}}">
                                    <label>Base Variation Name (opt)</label>
                                    <span class="helper-text">For the short variation name (i.e. 50 License Pack)</span>
                                </div>
                            </div>
                        @else
                            <div class="col-md-6 col-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="variation_name"
                                           value="{{$item->variation_name}}">
                                    <label>Variation Name</label>
                                    <span class="helper-text">For the short variation name (i.e. 50 License Pack)</span>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="row mt-3">
                        <div class="col-xl-6">
                            <input type="submit" class="btn btn-outline-primary wait" data-message="Updating Variations.."
                                   value="Save and Continue">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
