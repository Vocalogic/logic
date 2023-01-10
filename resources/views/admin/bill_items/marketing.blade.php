<div class="row g-2">
    <div class="col-md-4 col-4">
        <div class="form-floating">
            <input type="file" name="slick_id" class="drop"
                   data-default-file="{{$item->slick_id ? _file($item->slick_id)?->relative : null}}"/>
            <label>Product Standalone Marketing Slick</label>
            <span class="helper-text">Select a PDF to attach to quotes if this product is found inside the quote.</span>
        </div>

        <div class="form-floating mt-3">
            <input type="file" name="photo_id" class="drop"
                   data-default-file="{{$item->photo_id ? _file($item->photo_id)?->relative : null}}"/>
            <label>Product Photo (transparent png, 750x750)</label>
            <span class="helper-text">Select a photo for quotes and feature comparison materials.</span>
        </div>


    </div>

    <div class="col-md-8 col-8">
        <div class="row">
            <div class="col-lg-4">
                <div class="form-floating">
                    <input type="text" class="form-control" name="feature_priority"
                           value="{{$item->feature_priority}}">
                    <label>Feature Priority (lower = closer to top)</label>
                    <span class="helper-text">Enter a sort value for this product (ex. 1 = first, 100 = last)</span>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="form-floating">
                    <input type="text" class="form-control" name="msrp_note"
                           value="{{$item->msrp_note}}">
                    <label>MSRP Note to Guests</label>
                    <span class="helper-text">This can be when guests are looking at MSRP pricing and a badge to let them know special pricing is available, advertise coupon, etc.</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">


                <div class="form-floating">
                    <input type="text" class="form-control" name="feature_headline"
                           value="{{$item->feature_headline}}">
                    <label>Feature Headline (Subheading for Feature Comparisons)</label>
                    <span class="helper-text">Enter a short 5-6 word sentence for the column. (i.e. 16-line Video IP Phone)</span>
                </div>
                <div class="form-floating">
        <textarea class="form-control" style="height:200px;"
                  name="feature_list">{{$item->feature_list}}</textarea>
                    <label>Feature List (one entry per line)</label>
                    <span class="helper-text">Enter a bulleted list of features for this product (short)</span>
                </div>

                <div class="form-floating">
                    {!! Form::select('shop_show', [1 => 'Yes', 0 => 'No'], $item->shop_show, ['class' => 'form-select']) !!}
                    <label>Show item in Shop?</label>
                    <span class="helper-text">If no, this will not be linked to or displayed for more info.</span>
                </div>

            </div>
        </div>

    </div>

    <div class="col-lg-12">
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
