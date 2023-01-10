@extends('layouts.admin', ['title' => "Create/Modify Product", 'crumbs' => $crumbs])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">{{$item->name ?: "Create new $type"}}</h1>
            <small class="text-muted">{{$item->description ?: null}}</small>
        </div>
    </div> <!-- .row end -->

@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">

            @if($item->parent)
                <div class="alert {{bma()}}info">
                    This item is a variation of
                    <a href="/admin/category/{{$item->parent->category->id}}/items/{{$item->parent->id}}">
                        {{$item->parent->name}}
                    </a>.
                    This item will not be shown directly in the shop but will be shown as a different variation of the
                    item to select and purchase.
                </div>
            @endif

            @if($item->id && setting('quotes.openai'))
                <a class="btn btn-primary" href="/admin/category/{{$item->category->id}}/items/{{$item->id}}/marketing">Generate Descriptions with OpenAI (BETA)</a>
            @endif

            <form method="POST"
                  action="{{$item->id ? "/admin/category/{$cat->id}/items/{$item->id}" :"/admin/category/{$cat->id}/items"}}"
                  enctype="multipart/form-data">
                @method($item->id ? 'PUT' : 'POST')
                @csrf
                <div class="card-body step-app steps mt-3">

                    <ul class="step-steps">
                        <li data-step-target="step1"><span class="fa fa-credit-card"></span> Product Specifications</li>
                        <li data-step-target="step2"><span class="fa fa-user"></span> Pricing</li>
                        @if($item->id)
                            <li data-step-target="tags"><span class="fa fa-tag"></span> Tags/Addons</li>
                            <li data-step-target="faq"><span class="fa fa-question"></span> FAQ</li>
                        @endif
                        <li data-step-target="step3"><span class="fa fa-share-alt"></span> Marketing</li>
                        <li data-step-target="reserved"><span class="fa fa-exclamation"></span> Reservation</li>
                        <li data-step-target="requirements"><span class="fa fa-database"></span> Data Requirements</li>

                    </ul>

                    <div class="step-content">

                        <div class="step-tab-panel" data-step="step1">
                            <div class="row g-2 mb-2">
                                <div class="col-md-3 col-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="code" value="{{$item->code}}">
                                        <label>Product Code</label>
                                        <span
                                            class="helper-text">Enter a code to define this product. (ie. O365-SEAT)</span>
                                    </div>
                                </div>

                                <div class="col-md-6 col-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="name" value="{{$item->name}}">
                                        <label>Product Name</label>
                                        <span class="helper-text">Enter name to be used on invoice/quote (ie. My Monthly Service>)</span>
                                    </div>
                                </div>

                                <div class="col-md-3 col-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="on_hand"
                                               value="{{$item->on_hand}}">
                                        <label>QTY on Hand (opt)</label>
                                        <span class="helper-text">If tracking on-hand count, enter how many allowed to sell.</span>
                                    </div>
                                </div>


                            </div> <!-- .row end -->

                            <div class="row g-2 mb-2">
                                <div class="col-md-8 col-8">
                                    <div class="form-floating">
                                        <textarea class="form-control" style="height: 140px;"
                                                  name="description">{!! $item->description !!}</textarea>
                                        <label>Product Description (for Quote/Invoice)</label>
                                        <span class="helper-text">Enter the description to be used on quotes and invoices</span>
                                    </div>
                                </div>
                                @if(!$item->parent_id)
                                    <div class="col-md-4 col-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="variation_category"
                                                   value="{{$item->variation_category}}">
                                            <label>Variation Category (opt)</label>
                                            <span class="helper-text">If providing variations what is the category (i.e. Number of Licenses)</span>
                                        </div>
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="variation_name"
                                                   value="{{$item->variation_name}}">
                                            <label>Base Variation Name (opt)</label>
                                            <span class="helper-text">For the short variation name (i.e. 50 License Pack)</span>
                                        </div>

                                    </div>
                                @else
                                    <div class="col-md-4 col-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="variation_name"
                                                   value="{{$item->variation_name}}">
                                            <label>Variation Name</label>
                                            <span class="helper-text">For the short variation name (i.e. 50 License Pack)</span>
                                        </div>
                                    </div>
                                @endif

                            </div>

                            <div class="row g-2">
                                <div class="col-md-12 col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" style="height: 200px;"
                                                  name="marketing_description">{!! $item->marketing_description !!}</textarea>
                                        <label>Marketing Description (for shop)</label>
                                        <span
                                            class="helper-text">Enter markdown-enabled description for shop details.</span>
                                    </div>
                                </div>
                            </div>

                            <hr/>
                            <div class="row g-2 mt-3">
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_shipped" value="1"
                                               id="shipped" {{$item->is_shipped ? "checked" : null}}>
                                        <label class="form-check-label" for="shipped">Item is Shipped to
                                            Customer</label>
                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="track_qty" value="1"
                                               id="track_qty" {{$item->track_qty ? "checked" : null}}>
                                        <label class="form-check-label" for="track_qty">Track Inventory Quantity when
                                            Sold?</label>
                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="allow_backorder" value="1"
                                               id="allow_backorder" {{$item->allow_backorder ? "checked" : null}}>
                                        <label class="form-check-label" for="allow_backorder">Allow backorder if out of
                                            stock?</label>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <div class="step-tab-panel" data-step="step2">

                            <div class="row g-2">
                                <div class="col-md-6 col-6">


                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" name="nrc"
                                                       value="{{moneyFormat($item->nrc,2)}}">
                                                <label>Product Selling Price (One-Time)</label>
                                                <span class="helper-text">Enter the non-recurring cost for the purchase of this product.</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">

                                            <div class="form-floating">
                                                <input type="text" class="form-control" name="msrp"
                                                       value="{{moneyFormat($item->msrp,2)}}">
                                                <label>Product MSRP (shown to public/guests)</label>
                                                <span class="helper-text">Enter MSRP to show your discounted price to registered customers.</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-lg-6">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" name="min_price"
                                                       value="{{moneyFormat($item->min_price,2)}}">
                                                <label>Minimum Selling Price</label>
                                                <span class="helper-text">Enter the minimum amount allowed for sales agents to sell this item for.</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">

                                            <div class="form-floating">
                                                <input type="text" class="form-control" name="max_price"
                                                       value="{{moneyFormat($item->max_price,2)}}">
                                                <label>Maximum Selling Price</label>
                                                <span class="helper-text">Enter maximum amount allowed for sales agents to sell this item for.</span>
                                            </div>
                                        </div>

                                        @if(setting('quotes.selfterm') == 'Yes' && $item->id)
                                            <div class="col-lg-12">
                                                <div class="card mt-3">
                                                    <div class="card-body">

                                                        <h5 class="card-title mt-3">
                                                            Self-Checkout Contract Discounts
                                                        </h5>
                                                        <p>
                                                            You currently have guest self-contracting enabled. This
                                                            means that if a customer opts to automatically enroll in a
                                                            contract with you for monthly services, you can set discount
                                                            percentages <code>based on the MSRP</code> to show customers
                                                            instant savings.
                                                        </p>
                                                        @include('admin.bill_items.term_discount')
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6 col-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="ex_capex"
                                               value="{{moneyFormat($item->ex_capex,2)}}">
                                        <label>Product Cost (Capital Expense)</label>
                                        <span
                                            class="helper-text">Enter the average cost this item costs you to obtain.</span>
                                    </div>
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="ex_capex_description"
                                               value="{{$item->ex_capex_description}}">
                                        <label>Capital Expense Description</label>
                                        <span class="helper-text">Enter a description (i.e. This is the price from the vendor)</span>
                                    </div>

                                    <div class="form-floating mb-3">
                                        {!! Form::select('ex_capex_once', [0 => 'No', 1 => 'Yes'], $item->ex_capex_once, ['class' => 'form-control']) !!}
                                        <label>Capital Expense Once?</label>
                                        <span class="helper-text">Yes = Once single capex charge regardless of qty, No = Calculate expense * qty per item. </span>
                                    </div>


                                    @if($item->id)
                                        @include('admin.bill_items.pricingHelper', ['item' => $item])
                                    @endif


                                </div>
                            </div> <!-- .row end -->
                        </div>

                        @if($item->id)
                            <div class="step-tab-panel" data-step="tags">
                                <div class="row">
                                    <div class="col-lg-5">
                                        @include('admin.bill_items.tags')
                                    </div>
                                    <div class="col-lg-7">
                                        @include('admin.bill_items.addons')
                                    </div>
                                </div>
                            </div>

                            <div class="step-tab-panel" data-step="faq">
                                @include('admin.bill_items.faq')
                            </div>
                        @endif

                        <div class="step-tab-panel" data-step="step3">
                            @include('admin.bill_items.marketing')
                        </div>

                        <div class="step-tab-panel" data-step="reserved">
                            @include('admin.bill_items.reserved')
                        </div>
                        <div class="step-tab-panel" data-step="requirements">
                            @include('admin.bill_items.requirements')
                        </div>


                    </div>


                    <div class="step-footer d-flex">
                        <button class="btn step-btn" data-step-action="prev">< Previous Section</button>
                        <button class="btn step-btn" data-step-action="next">Next Section ></button>
                    </div>
                    <input type="submit" name="save" value="Save Product" class="mt-2 btn btn-secondary wait">
                    @if($item->id)
                        <a href="/admin/category/{{$cat->id}}/items/{{$item->id}}"
                           class="btn btn-danger confirm mt-2"
                           data-method="DELETE"
                           data-message="Are you sure you want to remove this item?">
                            <i class="fa fa-trash"></i> Remove
                        </a>
                        @if(!$item->parent)
                            <a class="live mt-2 btn btn-{{bm()}}primary" data-title="Variation to {{$item->name}}"
                               href="/admin/category/{{$item->category->id}}/items/{{$item->id}}/variation">
                                <i class="fa fa-recycle"></i> Add Variation
                            </a>

                        @endif

                        <a class="live mt-2 btn btn-{{bm()}}info" data-title="Move Category for {{$item->name}}"
                           href="/admin/category/{{$item->category->id}}/items/{{$item->id}}/category">
                            <i class="fa fa-arrow-right"></i> Change Category
                        </a>

                    @endif


                </div>
            </form>
        </div>

    </div>


    @if($item->id)
        <div class="modal fade" id="newTag" tabindex="-1" role="dialog" aria-labelledby="liveModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Assign Tag to {{$item->name}}</h5>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="/admin/category/{{$cat->id}}/items/{{$item->id}}?assign=tag">
                            @method('PUT')
                            @csrf
                            <h6 class="fw-bold">Select Tag to Add</h6>
                            <div class="row mt-2">
                                <div class="col-lg-6 col-md-12">
                                    <div class="form-floating">
                                        {!! Form::select('tag', \App\Models\Tag::selectable($cat), null, ['class' => "form-select"]) !!}
                                        <label>Select Tag</label>
                                        <span class="helper-text">Select tag to add to item.</span>
                                    </div>
                                    <input type="submit" name="submit" class="btn btn-primary" value="Add Tag">
                                </div>
                                <div class="col-lg-6">
                                    <h6 class="fw-bold">Assigned Tags</h6>

                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Tag</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($item->tags as $tag)
                                            <tr>
                                                <td>{{$tag->tag->name}}</td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
