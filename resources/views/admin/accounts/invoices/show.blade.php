<h4 class="title">Invoice #{{$invoice->id}} ({{$invoice->status}})</h4>
<div class="row">
    <div class="col-lg-9">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="/admin/invoices/{{$invoice->id}}/add">
                    @method('POST')
                    @csrf
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th class="text-center">Item</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">QTY</th>
                            <th class="text-center">Total</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($invoice->items as $item)
                            <tr>
                                @if($item->item)
                                    <td>
                                        <a class="live" data-title="{{$item->name}}"
                                           href="/admin/accounts/{{$invoice->account->id}}/invoices/{{$invoice->id}}/item/{{$item->id}}">
                                            <strong>[{{$item->item->code}}] {{$item->item->name}}</strong></a>
                                            <br/>
                                        <small
                                            class="text-muted">{!! $item->description ?: $item->item->description !!}</small>
                                    </td>

                                @else
                                    <td>
                                        <a class="live" data-title="{{$item->name}}"
                                           href="/admin/accounts/{{$invoice->account->id}}/invoices/{{$invoice->id}}/item/{{$item->id}}"><strong>{{$item->name}}</strong></a>
                                        <br/>
                                        <small class="text-muted">{{$item->description}}</small>
                                    </td>
                                @endif

                                <td class="text-center">${{moneyFormat($item->price)}}</td>
                                <td class="text-center">{{$item->qty}}  <a class="confirm text-danger"
                                                                           data-message="Are you sure you want to remove this item?"
                                                                           data-method="DELETE"
                                                                           href="/admin/invoices/{{$invoice->id}}/rem/{{$item->id}}">
                                        <i class="fa fa-trash"></i></a></td>
                                <td class="text-center">${{moneyFormat($item->price * $item->qty)}}

                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td>
                                <input type="text" class="form-control" name="item">
                            </td>
                            <td><input type="text" class="form-control" name="price"></td>
                            <td><input type="text" class="form-control" name="qty" value="1"></td>
                            <td><input type="submit" name="add" value="+" class="btn btn-primary">
                            </td>
                        </tr>

                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="text-align:right;"><strong>Total:</strong></td>
                            <td>${{moneyFormat($invoice->total)}}</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="text-align:right;"><strong>Balance:</strong></td>
                            <td>${{moneyFormat($invoice->balance)}}</td>
                        </tr>


                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        @if($invoice->transactions()->count())
            @include('admin.invoices.trans')
        @endif
    </div>

    <div class="col-lg-3">
        @include('admin.invoices.actions')
    </div>

</div>


<div class="modal fade" id="products" tabindex="-1">
    <div class="modal-dialog modal-dialog-vertical modal-dialog-scrollable">
        <div class="modal-content">

            <div class="px-xl-4 modal-header">
                <h5 class="modal-title">Add Invoice Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom_scroll">

                <table class="table datatable">
                    <thead>
                    <tr>
                        <th>Item</th>
                        <th>Price</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(\App\Models\BillCategory::where('type', \App\Enums\Core\BillItemType::PRODUCT)->get() as $cat)

                        @foreach($cat->items as $item)
                            <tr>
                                <td>
                                    <a href="/admin/invoices/{{$invoice->id}}/add/{{$item->id}}">[{{$item->code}}
                                        ] {{$item->name}}</a><br/><small
                                        class="text-muted">{{$item->category->name}}</small></td>
                                <td>${{moneyFormat($item->nrc)}}</td>
                            </tr>
                        @endforeach
                    @endforeach


                    </tbody>
                </table>


            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="px-xl-4 modal-header">
                <h5 class="modal-title">Apply Payment to Invoice #{{$invoice->id}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>You are about to apply (or authorize) a payment with a card on file. If you select
                    a credit card, it will attempt to authorize the card. Otherwise, a payment will be applied based
                    on the information given.
                </p>

                <form method="POST" action="/admin/accounts/{{$account->id}}/invoices/{{$invoice->id}}/auth">
                    @csrf
                    @method('POST')
                    <div class="row">

                        <div class="col-lg-6">
                            <div class="form-floating">
                                <select name="pmethod" class="form-control">
                                    <option value="">-- Select Method --</option>
                                    @foreach(\App\Enums\Core\PaymentMethod::cases() as $opt)
                                        @if($opt->canUse($account))
                                            <option
                                                value="{{$opt->value}}">{{$opt->value}} {{$opt->getAdditionalDetails($account)}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <label>Select Payment Method:</label>
                                <span class="helper-text">Select the payment method to use</span>

                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-floating">
                                <div class="col-lg-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="amount"
                                               value="{{moneyFormat($invoice->balance)}}">
                                        <label>Amount to Pay</label>
                                        <span class="helper-text">Enter the amount to authorize</span>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-12 mt-2">
                            <div class="form-floating">
                                <div class="col-lg-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="details">
                                        <label>Additional Details (check no, other info)</label>
                                        <span class="helper-text">Optionally enter in additional information.</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-3 text-center">
                            <input type="submit" name="submit" class="btn btn-primary wait"
                                   value="Authorize/Post Transaction">
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
