@extends('layouts.admin', ['title' => "Invoice #$invoice->id",
'crumbs' => [
    "/admin/accounts/{$invoice->account->id}" => $invoice->account->name,
    "Invoice #$invoice->id"
]

])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Invoice #{{$invoice->id}} for
                <a href="/admin/accounts/{{$invoice->account_id}}">{{$invoice->account->name}}</a>
                ({{$invoice->status}})
            </h1>
            <small class="text-muted">
                Total: <b>${{moneyFormat($invoice->total)}}</b> | Balance: <b>${{moneyFormat($invoice->balance)}}</b>
                    | Purchase Order: <a class="live" data-title="Invoice #{{$invoice->id}} Settings"
                                         href="/admin/invoices/{{$invoice->id}}/settings"><b>{{$invoice->po ?: "N/A"}}</b>
                    </a>
                @if($invoice->account->agent)
                    | Agent: <b>{{$invoice->account->agent->name}}</b>
                @endif
                @if($invoice->account->affiliate)
                    | Affiliate: <b>{{$invoice->account->affiliate->name}}</b>
                @endif
            </small>
        </div>

    </div> <!-- .row end -->

@endsection

@section('content')

<div class="row">
    <div class="col-lg-9 col-xs-12">
        <div class="card border-{{$invoice->status->getColor()}}">
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
                        @if(!$invoice->transactions()->count())
                        <tr>
                            <td>
                                <input type="text" class="form-control" name="item">
                            </td>
                            <td><input type="text" class="form-control" name="price"></td>
                            <td><input type="text" class="form-control" name="qty" value="1"></td>
                            <td><input type="submit" name="add" value="+" class="btn btn-primary">
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


                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        @if($invoice->transactions()->count())
            @include('admin.invoices.trans')
        @endif
    </div>

    <div class="col-lg-3 col-xs-12">
        @include('admin.invoices.actions')
    </div>

</div>

<x-modal name="products" title="Add Invoice Item">
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
                <input type="submit" name="submit" class="btn btn-primary wait"
                       data-anchor=".paymentForm"
                       value="Authorize/Post Transaction">
            </div>

        </div>
    </form>
</x-modal>

@endsection
