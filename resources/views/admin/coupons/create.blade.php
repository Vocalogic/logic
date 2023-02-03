@extends('layouts.admin', ['title' => 'Coupon Management', 'crumbs' => [
    "/admin/coupons" =>  "Coupon Management",
    $coupon->id ? $coupon->coupon : "Create new Coupon"
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">{{$coupon->id ? "$coupon->coupon - $coupon->name" : "Create new Coupon"}}</h1>
            <small class="text-muted">Manage Coupons that Customers can apply at checkout</small>
        </div>
    </div> <!-- .row end -->

@endsection
@section('content')
    <div class="row">
        <div class="col-lg-6">

            <div class="card">
                <div class="card-body">
                    <form method="post" action="/admin/coupons/{{$coupon->id ? "$coupon->id" : null}}">
                        @method($coupon->id ? 'PUT' : "POST")
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 mb-2"><h6 class="card-title">Coupon Details</h6></div>
                            <div class="col-lg-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="coupon"
                                           value="{{$coupon->coupon}}">
                                    <label>Coupon Code:</label>
                                    <span class="helper-text">Enter coupon (i.e. 50-OFF)</span>
                                </div>
                            </div>

                            <div class="col-lg-8">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="name"
                                           value="{{$coupon->name}}">
                                    <label>Coupon Name:</label>
                                    <span class="helper-text">Enter a name for this coupon. (i.e Get 50% off your first order!)</span>
                                </div>
                            </div>
                        </div>


                        <div class="row mt-2">
                            <div class="col-lg-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="start"
                                           value="{{$coupon->start ? $coupon->start->format("m/d/y h:ia") : null}}">
                                    <label>Start Date/Time:</label>
                                    <span
                                        class="helper-text">Enter the start date and time for this coupon to be active</span>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-floating">
                                    <input type="text" id="endTime" class="form-control" name="end"
                                           value="{{$coupon->end ? $coupon->end->format("m/d/y h:ia") : null}}">
                                    <label for="endTime">End Date/Time:</label>
                                    <span
                                        class="helper-text">Enter the start date and time for this coupon to be active</span>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-lg-12 mb-2"><h6 class="card-title">Coupon Restrictions</h6></div>

                            <div class="col-lg-4">
                                <div class="form-floating">
                                    {!! Form::select('total_invoice', [0 => 'No', 1 => 'Yes'], $coupon->total_invoice, ['class' => 'form-control']) !!}
                                    <label>Discount Invoice?</label>
                                    <span class="helper-text">If yes, this coupon will apply to all products and services and
                                    will not be restricted.</span>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-floating">
                                    {!! Form::select('new_accounts_only', [0 => 'No', 1 => 'Yes'], $coupon->new_accounts_only, ['class' => 'form-control']) !!}
                                    <label>Only New Accounts?</label>
                                    <span class="helper-text">If yes, this coupon will only apply if this is a new account.</span>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="remaining"
                                           value="{{$coupon->remaining ?: -1}}">
                                    <label for="endTime">Number of Coupons Remaining:</label>
                                    <span class="helper-text">Enter the number remaining (-1 for unlimited)</span>
                                </div>
                            </div>
                        </div>


                        <div class="row mt-2">
                            <div class="col-lg-12 mb-2"><h6 class="card-title">Coupon Configuration</h6></div>
                            <div class="col-lg-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="dollars_off"
                                           value="{{$coupon->dollars_off}}">
                                    <label>Specific Dollars Off?</label>
                                    <span
                                        class="helper-text">If you are giving a discount in dollars, enter the amount</span>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="percent_off"
                                           value="{{$coupon->percent_off}}">
                                    <label>Percentage Discount?</label>
                                    <span class="helper-text">If you are giving a percent-based discount, enter the percentage.</span>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="dollar_spend_required"
                                           value="{{$coupon->dollar_spend_required}}">
                                    <label>Minimum Spend Limit?</label>
                                    <span class="helper-text">What is the minimum amount a customer will need to purchase to apply this coupon?</span>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-lg-6">

                                <div class="form-floating">
                                    {!! Form::select('affiliate_id', \App\Models\Affiliate::getSelectable(), $coupon->affiliate_id, ['class' => 'form-control']) !!}
                                    <label>Select Affiliate</label>
                                    <span class="helper-text">Does this coupon belong to an affiliate?</span>
                                </div>

                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-lg-12 mb-2"><h6 class="card-title">Terms and Conditions</h6></div>

                            <div class="col-lg-12">
                                <div class="form-floating">
                                    <textarea class="form-control" name="details"
                                              style="height:150px;">{!! $coupon->details !!}</textarea>
                                    <label>Details / Terms</label>
                                    <span class="helper-text">If a customer enters this coupon, you can show additional terms
                                     and conditions for using this coupon.</span>
                                </div>

                            </div>

                            <div class="col-lg-12 mt-2">
                                <a class="text-danger confirm" data-method="DELETE"
                                   data-message="Are you sure you want to delete this coupon? It will be
                                   immediately removed from the store and invalidated."
                                   href="/admin/coupons/{{$coupon->id}}"><i class="fa fa-times"></i> Delete Coupon</a>

                                <button type="submit" class="btn btn-primary ladda pull-right" data-style="zoom-out">
                                    <i class="fa fa-save"></i> Save Coupon
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @if($coupon->id && !$coupon->total_invoice)
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Products/Services Enabled</h6>
                        <p>Manage your products and services that can your coupon can be used by.</p>
                        <a href="#addItem" data-bs-toggle="modal" class="btn btn-primary mb-3"><i
                                class="fa fa-plus"></i> Add Item/Service</a>

                        <table class="table table-sm table-striped">
                            <thead>
                            <tr>
                                <th>Item</th>
                                <th>Min</th>
                                <th>Max</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($coupon->items as $item)
                                <tr>
                                    <td><a class="live" data-title="Update Product/Service"
                                           href="/admin/coupons/{{$coupon->id}}/items/{{$item->id}}">{{$item->item->name}}</a>
                                    </td>
                                    <td>{{$item->min_qty}}</td>
                                    <td>{{$item->max_qty}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="modal fade" id="addItem" tabindex="-1" role="dialog" aria-labelledby="liveModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select Product or Service to Add</h5>
                </div>
                <div class="modal-body newItem">
                    <form method="post" action="/admin/coupons/{{$coupon->id}}/items">
                        @method('POST')
                        @csrf
                        <div class="row mt-2">
                            <div class="col-lg-12 col-md-12">
                                <div class="form-floating">
                                    {!! Form::select('bill_item_id', \App\Models\BillItem::selectable(), null, ['class' => 'form-select', 'id' => 'selectmodal']) !!}
                                    <label>Select Product/Service</label>
                                    <span
                                        class="helper-text">Select a product or service that this coupon will work for</span>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">

                            <div class="col-lg-6 col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="min_qty" value="1" class="form-control">
                                    <label>Min Qty Required</label>
                                    <span class="helper-text">Enter a minimum quantity</span>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="max_qty" value="1" class="form-control">
                                    <label>Max Qty Allowed</label>
                                    <span class="helper-text">Enter a maxmium quantity</span>
                                </div>
                            </div>

                        </div>


                        <div class="col-lg-12 col-md-12 mt-3">
                            <button type="submit" class="btn btn-primary ladda" data-style="zoom-out">
                                <i class="fa fa-save"></i> Update Coupon
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection
