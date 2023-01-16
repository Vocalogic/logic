<div class="card">
    <div class="card-body">
        <h6 class="card-title">Photos</h6>
        <p class="card-text">Upload a Sales Slick and Photos for your Shop and for Quotes/Invoices</p>
        <form method="POST" action="/admin/category/{{$cat->id}}/items/{{$item->id}}/photos" enctype="multipart/form-data">
            @method('PUT')
            @csrf

            <div class="row g-2">

                <div class="col-6">
                    <div class="form-floating">
                        <input type="file" name="photo_id" class="drop"
                               data-default-file="{{$item->photo_id ? _file($item->photo_id)?->relative : null}}"/>
                        <label>{{ucFirst($item->type)}} Photo (transparent png, 750x750)</label>
                        <span class="helper-text">Select a photo for quotes and feature comparison materials.</span>
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-floating">
                        <input type="file" name="slick_id" class="drop"
                               data-default-file="{{$item->slick_id ? _file($item->slick_id)?->relative : null}}"/>
                        <label>{{ucFirst($item->type)}} Standalone Marketing Slick</label>
                        <span class="helper-text">Select a PDF to attach to quotes if this product is found inside the quote.</span>
                    </div>
                </div>

                <div class="col-12 mt-3">
                    <div class="row">

                        <div class="col-md-4 col-4">
                            <div class="form-floating">
                                <input type="file" name="photo_2" class="drop"
                                       data-default-file="{{$item->photo_2 ? _file($item->photo_2)?->relative : null}}"/>
                                <label>Optional Photo 2 (transparent png, 750x750)</label>
                                <span class="helper-text">Add additional photos for this item for the shop.</span>
                            </div>
                        </div>

                        <div class="col-md-4 col-4">
                            <div class="form-floating">
                                <input type="file" name="photo_3" class="drop"
                                       data-default-file="{{$item->photo_3 ? _file($item->photo_3)?->relative : null}}"/>
                                <label>Optional Photo 3 (transparent png, 750x750)</label>
                                <span class="helper-text">Add additional photos for this item for the shop.</span>
                            </div>
                        </div>

                        <div class="col-md-4 col-4">
                            <div class="form-floating">
                                <input type="file" name="photo_4" class="drop"
                                       data-default-file="{{$item->photo_4 ? _file($item->photo_4)?->relative : null}}"/>
                                <label>Optional Photo 4 (transparent png, 750x750)</label>
                                <span class="helper-text">Add additional photos for this item for the shop.</span>
                            </div>
                        </div>

                        <div class="col-md-4 col-4">
                            <div class="form-floating">
                                <input type="file" name="photo_5" class="drop"
                                       data-default-file="{{$item->photo_5 ? _file($item->photo_5)?->relative : null}}"/>
                                <label>Optional Photo 5 (transparent png, 750x750)</label>
                                <span class="helper-text">Add additional photos for this item for the shop.</span>
                            </div>
                        </div>


                    </div>
                </div>

            </div> <!-- .row end -->

            <div class="row mt-3">
                <div class="col-xl-6">
                    <input type="submit" class="btn btn-outline-primary wait" data-message="Updating Photos.."
                           value="Save and Continue">
                </div>
            </div>


        </form>
    </div>
</div>
