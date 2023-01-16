<div class="card">
    <div class="card-body">
        <h6 class="card-title">Pricing</h6>
        <p class="card-text">Define your pricing and your costs for this item</p>
        <form method="POST" action="/admin/category/{{$cat->id}}/items/{{$item->id}}/pricing">
            @method('PUT')
            @csrf
            <div class="row g-2">
                <div class="col-md-6 col-6">
                    <div class="row">
                        <div class="col-lg-4">
                            @if($item->type == 'services')
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="mrc"
                                           value="{{moneyFormat($item->mrc,2)}}">
                                    <label>Recurring Selling Price</label>
                                    <span
                                        class="helper-text">Enter the recurring cost for the purchase of this service.</span>
                                </div>
                            @else
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="nrc"
                                           value="{{moneyFormat($item->nrc,2)}}">
                                    <label>One-Time Price</label>
                                    <span
                                        class="helper-text">Enter the one-time cost for the purchase of this product.</span>
                                </div>
                            @endif
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="msrp"
                                       value="{{moneyFormat($item->msrp,2)}}">
                                <label>Service MSRP</label>
                                <span class="helper-text">Enter MSRP to show your discounted price to registered customers.</span>
                            </div>
                        </div>

                        @if($item->type == 'services')
                            <div class="col-lg-4">
                                <div class="form-floating">
                                    {!! Form::select('frequency', \App\Enums\Core\BillFrequency::getSelectable(), $item?->frequency?->value ?: 'MONTHLY', ['class' => 'form-control']) !!}
                                    <label>Billing Frequency</label>
                                    <span
                                        class="helper-text">Select how often this service will be billed.</span>
                                </div>
                            </div>
                        @endif


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

                    </div>

                    @if(setting('quotes.selfterm') == 'Yes' && $item->id)
                        <div class="card mt-3">
                            <div class="card-body">

                                <h5 class="card-title mt-3">
                                    Self-Checkout Contract Discounts
                                </h5>
                                <p>
                                    You currently have guest self-contracting enabled. This means that
                                    if a customer opts to automatically enroll in a contract with you
                                    for monthly services, you can set discount percentages
                                    <code>based on the MSRP</code> to show customers instant savings.
                                </p>
                                @include('admin.bill_items.term_discount')
                            </div>
                        </div>
                    @endif


                </div>

                <div class="col-md-6 col-6">
                    @if($item->type == 'services')
                        <div class="form-floating">
                            <input type="text" class="form-control" name="ex_opex"
                                   value="{{moneyFormat($item->ex_opex,2)}}">
                            <label>Service Cost (Operational Expense)</label>
                            <span
                                class="helper-text">Enter the price per month this service costs you.</span>
                        </div>
                    @else
                        <div class="form-floating">
                            <input type="text" class="form-control" name="ex_capex"
                                   value="{{moneyFormat($item->ex_capex,2)}}">
                            <label>Product Cost (Captial Expense)</label>
                            <span
                                class="helper-text">Enter the price for this product</span>
                        </div>
                    @endif

                    @if($item->type == 'services')
                        <div class="form-floating">
                            <input type="text" class="form-control" name="ex_opex_description"
                                   value="{{$item->ex_opex_description}}">
                            <label>Operational Expense Description</label>
                            <span
                                class="helper-text">Enter a description (i.e. This is the price from the vendor)</span>
                        </div>
                    @else
                        <div class="form-floating">
                            <input type="text" class="form-control" name="ex_capex_description"
                                   value="{{$item->ex_capex_description}}">
                            <label>Captial Expense Description</label>
                            <span
                                class="helper-text">Enter a description (i.e. This is the price from the vendor)</span>
                        </div>
                    @endif

                    @if($item->type == 'services')
                        <div class="form-floating">
                            {!! Form::select('ex_opex_once', [0 => 'No', 1 => 'Yes'], $item->ex_opex_once, ['class' => 'form-control']) !!}
                            <label>Operational Expense Once?</label>
                            <span class="helper-text">Yes = Once single opex charge regardless of qty, No = Calculate expense * qty per item. </span>
                        </div>
                    @endif




                </div>
            </div> <!-- .row end -->
            <div class="row mt-3">
                    @include('admin.bill_items.pricingHelper', ['item' => $item])
            </div>
            <div class="row mt-3">
                @include('admin.bill_items.pricing.term_discount')
            </div>


            <div class="row mt-3">
                <div class="col-xl-6">
                    <input type="submit" class="btn btn-outline-primary wait" data-message="Updating Pricing.."
                           value="Save and Continue">
                </div>
            </div>
        </form>

    </div>
</div>
