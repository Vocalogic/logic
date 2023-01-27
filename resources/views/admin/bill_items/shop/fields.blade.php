<div class="row">
    <div class="col-lg-12">
        <div class="card mt-3 mb-3">
            <div class="card-body">
                <h6 class="card-title">Shop Settings</h6>
                <p>
                    The items below are related to presenting your product or service in your ecommerce shop. These
                    details are not used in the Logic admin area and are just for SEO and product representation.
                </p>

                <form method="POST" action="/admin/category/{{$cat->id}}/items/{{$item->id}}/shop">
                    @method('PUT')
                    @csrf

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-floating">
                                {!! Form::select('shop_show', [1 => 'Yes', 0 => 'No'], $item->shop_show, ['class' => 'form-select']) !!}
                                <label>Show item in Shop?</label>
                                <span
                                    class="helper-text">If no, this will not be linked to or displayed for more info.</span>
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

                    <div class="row mt-3">
                        <div class="col-md-12 col-12">
                            <div class="form-floating">
                                        <textarea class="form-control" style="height: 100px;"
                                                  name="confirmation_dialog">{!! $item->confirmation_dialog !!}</textarea>
                                <label>Confirmation Dialog</label>
                                <span class="helper-text">When a customer selects this item to add to their cart, you
                                can add a pop-up dialog message. (Include things that may be required to purchase
                                the item)</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="row g-2">
                            <div class="col-md-12 col-12">
                                <div class="form-floating">
                                        <textarea class="tinymce" style="height: 200px;"
                                                  name="marketing_description">{!! $item->marketing_description !!}</textarea>
                                    <label>Marketing Description for Shop</label>
                                    <span
                                        class="helper-text">Enter markdown-enabled description for shop details.</span>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row mt-3">
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
                                <span class="helper-text">Enter a bulleted list of features for this product</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-xl-6">
                            <input type="submit" class="btn btn-outline-primary wait"
                                   data-message="Updating Shop Settings.."
                                   value="Save and Continue">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
