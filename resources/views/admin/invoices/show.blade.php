@extends('layouts.admin', [
    'title' => "Invoice #$invoice->id for {$invoice->account->name}",
    'crumbs' => [
        "/admin/accounts/{$invoice->account->id}" => $invoice->account->name,
        "Invoice #$invoice->id"
    ],
    'log' => $invoice->logLink
])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Invoice #{{$invoice->id}} for
                <a href="/admin/accounts/{{$invoice->account_id}}">{{$invoice->account->name}}</a>
                ({{$invoice->status}})
            </h1>

        </div>

    </div> <!-- .row end -->

@endsection

@section('content')


<div class="row">
    <div class="offset-1 col-lg-9 col-xs-12">
        @include('admin.invoices.actions')

        <div class="card ribbon-box border">
            <div class="card-body">
                <div class="ribbon-two ribbon-two-{{$invoice->status->getColor()}}"><span>{{$invoice->status}}</span></div>
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
                        @foreach($invoice->items()->with(['item', 'item.category'])->get() as $item)
                            <tr>
                                @if($item->item)
                                    <td>
                                        <a class="live" data-title="{{$item->name}}"
                                           href="/admin/invoices/{{$invoice->id}}/item/{{$item->id}}">
                                            <strong>[{{$item->item->code}}] {{$item->item->name}}</strong></a>

                                        <br/>
                                        <small
                                            class="text-muted">{!! $item->description ?: $item->item->description !!}</small>
                                    </td>

                                @else
                                    <td>
                                        <a class="live" data-title="{{$item->name}}"
                                           href="/admin/invoices/{{$invoice->id}}/item/{{$item->id}}"><strong>{{$item->name}}</strong></a>
                                        @if(preg_match("/late fee/i", $item->name))
                                            <span data-bs-toggle='tooltip' title="Automatic late fee assessed" class="pull-right badge badge-outline-danger"><i class="fa fa-warning"></i> late fee</span>
                                        @endif
                                        <br/>
                                        <small class="text-muted">{{$item->description}}</small>
                                    </td>
                                @endif
                                <td class="text-center">${{moneyFormat($item->price)}} {!! $item->variation_detail !!}</td>
                                <td class="text-center">{{$item->qty}}</td>
                                <td class="text-center">${{moneyFormat($item->price * $item->qty)}}

                                </td>
                            </tr>
                        @endforeach
                        @if(!$invoice->transactions->count())
                        <tr>
                            <td>
                                <input type="text" class="form-control" name="item" placeholder="Enter new custom invoice item..">
                            </td>
                            <td><input type="text" class="form-control" name="price" placeholder="Enter Price"></td>
                            <td><input type="text" class="form-control" name="qty" value="1"></td>
                            <td><button type="submit" name="add" class="btn btn-primary ladda" data-effect="zoom-out">+</button>
                            </td>
                        </tr>
                        @endif
                        @if($invoice->tax > 0)
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td style="text-align:right;"><strong>Subtotal:</strong></td>
                                <td>${{moneyFormat($invoice->subtotal)}}</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td style="text-align:right;"><strong>Tax:</strong></td>
                                <td>${{moneyFormat($invoice->tax)}}</td>
                            </tr>
                        @endif
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
                        @if($invoice->po || $invoice->recurringProfile)
                        <tr>
                            <td colspan="4">
                                <small class="text-muted">
                                   @if($invoice->po)
                                    Purchase Order: <a class="live" data-title="Invoice #{{$invoice->id}} Settings"
                                                         href="/admin/invoices/{{$invoice->id}}/settings"><b>{{$invoice->po ?: "N/A"}}</b>
                                    </a>
                                   @endif
                                    @if($invoice->recurringProfile)
                                        | Billed from Recurring Profile <span class="text-info"> {{$invoice->recurringProfile->name}}</span>
                                    @endif

                                </small>
                            </td>
                        </tr>
                        @endif


                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        @if($invoice->transactions()->count())
            @include('admin.invoices.trans')
        @endif

        @if($invoice->status == \App\Enums\Core\InvoiceStatus::SENT)
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-body">
                        <i class="fa fa-info-circle"></i> This invoice is currently due on <strong>{{$invoice->due_on->format("m/d/y")}}</strong>.
                        <a class="live text-primary"
                           data-title="Change Due Date"
                           href="/admin/invoices/{{$invoice->id}}/due">Change due date?</a>
                    </div>
                </div>
            </div>
        @endif
    </div>


</div>

<x-modal name="products" title="Add Invoice Item">
    <table class="table datatable itemTable">
        <thead>
        <tr>
            <th>Item</th>
            <th>Price</th>
        </tr>
        </thead>
        <tbody>
        @foreach(\App\Models\BillCategory::with('items')->where('type', \App\Enums\Core\BillItemType::PRODUCT)->get() as $cat)

            @foreach($cat->items as $item)
                <tr>
                    <td>
                        <a class="wait" data-message="Adding to Invoice.." data-effect="rotateplane" data-anchor=".itemTable"
                            href="/admin/invoices/{{$invoice->id}}/add/{{$item->id}}">
                            [{{$item->code}}] {{$item->name}}</a><br/><small
                            class="text-muted">{{$cat->name}}</small></td>
                    <td>${{moneyFormat($item->nrc)}}</td>
                </tr>
            @endforeach
        @endforeach


        </tbody>
    </table>
</x-modal>


<x-modal name="paymentModal" title="Apply Payment to Invoice #{{$invoice->id}}">
    <p>You are about to apply (or authorize) a payment with a card on file. If you select
        a credit card, it will attempt to authorize the card. Otherwise, a payment will be applied based
        on the information given.
    </p>
    <form method="POST" action="/admin/invoices/{{$invoice->id}}/auth" class="paymentForm">
        @csrf
        @method('POST')
        <div class="row">

            <div class="col-lg-6">
                <div class="form-floating">
                    <select name="pmethod" class="form-control">
                        <option value="">-- Select Method --</option>
                        @foreach(\App\Enums\Core\PaymentMethod::cases() as $opt)
                            @if($opt->canUse($invoice->account))
                                <option
                                    value="{{$opt->value}}">{{$opt->value}} {{$opt->getAdditionalDetails($invoice->account)}}</option>
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
                <button type="submit" name="submit" class="btn btn-primary ladda" data-style="expand-left">
                    <i class="fa fa-credit-card"></i> Authorize/Post Transaction</button>
            </div>

        </div>
    </form>
</x-modal>

@endsection
