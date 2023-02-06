@include('pdf.pdf_styles')
<style>
    #pageWrapper {
        margin-left: 50px;
        margin-right: 50px;
        margin-top: 20px;
    }

    #summaryHeader {
        width: 100%;
        height: 125px;
    }

    #summaryHeader .logo {
        float: left;
        width: 50%;
        height: 100%;
    }


    .newpage {
        page-break-before: always;
    }

    #summaryHeader .logo img {
        max-width: 90%;
        max-height: 90%;
        padding-top: 20px;
        display: block;
        vertical-align: middle;
    }

    #summaryHeader .title {
        float: left;
        width: 50%;
        text-align: center;
        font-size: 24px;
        font-family: "Nunito-Bold", sans-serif;
        height: 100%;
    }

    #infoBlock {
        width: 100%;
        height: 160px;
    }

    #infoBlock .customerInfo {
        width: 50%;
        height: 100%;
        font-size: 14px;
        font-family: Nunito;
        float: left;
    }

    b {
        font-size: 16px;
        font-family: 'Nunito-Bold', sans-serif;
        font-weight: bold;
    }

    .helper-text b {
        font-size: 10px;
        font-family: 'Nunito-Bold', sans-serif;
        font-weight: bold;
    }

    #infoBlock .customerInfo p {
        font-size: 14px;
        font-family: 'Nunito', sans-serif;
    }

    #infoBlock .invoiceInfo {
        width: 50%;
        height: 100%;
        float: left;
        font-size: 14px;
        font-family: 'Nunito', sans-serif;
    }

    #summaryBlock {
        width: 100%;
        height: 410px;
        margin-top: 25px;
    }

    #summaryBlock .summaryleft {
        width: 50%;
        height: 100%;
        float: left;
    }

    #summaryBlock .help {
        padding-left: 20px;
        width: 46%;
        height: 100%;
        float: left;
    }

    #scissors {
        width: 100%;
        height: 30px;
        margin-top: 10px;
    }

    #cutoutBlock {
        width: 100%;
        height: 175px;
        margin-top: 15px;
    }

    #cutoutBlock .cutleft {
        width: 50%;
        float: left;
        text-align: center;
    }

    #cutoutBlock .cutright {
        width: 50%;
        float: left;
        text-align: center;
    }


    .table {
        border-collapse: collapse;
        font-size: 12px;
        font-family: 'Nunito', sans-serif;
    }

    .table thead {
        background-color: #34404f;
        color: #fff;
        font-family: 'Nunito-Bold', sans-serif;

    }

    .table tr:nth-child(even) {
        background-color: #f1f1f1;
    }

    .product-left {
        float: left;
        width: 80%;
    }

    .product-right {
        float: left;
        width: 20%;
    }

    .prodimg {
        max-width: 80px;
        max-height: 80px;
    }

</style>

@if($invoice->account->logo_id)
    <div id="watermark">
        <img src="{{_file($invoice->account->logo_id)?->internal}}">
    </div>
@else
    <div id="watermark"><img src="{{_file(setting('brandImage.watermark'))?->internal}}"></div>
@endif
<div class="break">
    <div id="pageWrapper">

        <div id="summaryHeader">
            <div class="logo">
                <img src="{{_file(setting('brandImage.light'))?->internal}}">
            </div>

            <div class="title">
                <h4>Account Summary</h4>
            </div>
        </div>

        <div class="clear"></div>

        <div id="infoBlock">
            <div class="customerInfo">
                <hr>
                <b>Customer Info</b>
                <hr>
                <p>
                    {{$invoice->account->name}}<br/>
                    {{$invoice->account->address}}
                    @if($invoice->account->address2)
                        {{$invoice->account->address2}}
                    @endif
                    <br/>
                    {{$invoice->account->city}}, {{$invoice->account->state}} {{$invoice->account->postcode}}

                </p>
            </div>

            <div class="invoiceInfo">
                <table border="0" width="100%" cellpadding="5">
                    <tr>
                        <td align="right"><strong>Invoice Date</strong></td>
                        <td>{{$invoice->created_at->format("m/d/Y")}}</td>
                    </tr>

                    <tr>
                        <td align="right"><strong>Invoice Number</strong></td>
                        <td>#{{$invoice->id}}</td>
                    </tr>

                    <tr>
                        <td align="right"><strong>Terms</strong></td>
                        <td>{{$invoice->account->net_terms ? $invoice->account->net_terms . " Days" : "Due on Receipt"}}</td>
                    </tr>
                    <tr>
                        <td align="right"><strong>Due On or Before</strong></td>
                        <td>{{$invoice->due_on->format("m/d/Y")}}</td>
                    </tr>

                    <tr>
                        <td align="right"><strong>Invoice Balance</strong></td>
                        <td>${{moneyFormat($invoice->balance)}}</td>
                    </tr>
                    @if($invoice->po)
                        <tr>
                            <td align="right"><strong>Purchase Order</strong></td>
                            <td>{{$invoice->po}}</td>
                        </tr>
                    @endif


                </table>
            </div>
        </div>

        <div class="clear"></div>


        <div id="summaryBlock">


            <div class="summaryleft">
                <hr>
                <b>Summary of Charges</b>
                <hr>
                <table border="0" cellpadding="5" cellspacing="0" width="100%" style="font-size: 14px;">
                    <tr bgcolor="#dedede">
                        <td width="75%">Previous Balance</td>
                        <td>${{moneyFormat($invoice->previous_balance)}}</td>
                    </tr>
                    <tr>
                        <td width="75%">New Charges</td>
                        <td>${{moneyFormat($invoice->total)}}</td>
                    </tr>
                    <tr bgcolor="#dedede">
                        <td width="75%">Past Due Balance</td>
                        <td>${{moneyFormat($invoice->account->past_due)}}</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <hr/>
                        </td>
                    </tr>

                    <tr>
                        <td width="75%"><b>Account Balance</b></td>
                        <td><b>${{moneyFormat($invoice->account->account_balance)}}</b></td>
                    </tr>
                </table>

                @if($invoice->account->past_due > 0)
                    <div style="border: 1px #001C6B dashed; padding:10px; text-align: center; margin-top: 20px;">
                        <b style="color: red;">NOTICE: Past Due Balance</b>
                        <p>
                            There is currently a past due amount on your account. Please contact as soon as possible
                            to correct. Past due balances can incur automatic suspensions of service if left unpaid.
                        </p>

                    </div>

                @endif


            </div>

            <div class="help">
                <hr>
                <b>Additional Information</b>
                <hr>
                {!! _markdown(setting('invoices.help')) !!}
            </div>
            <div class="clear"></div>


        </div>

        <div id="scissors">

            <p style="text-align:center; ">
                If paying by mail, please return this portion with your payment.
            </p>
        </div>


        <div id="cutoutBlock">

            <table style="border-collapse: collapse;" border="1" width="100%">
                <tr style="background-color: #312c2c; color: white;">
                    <td align="center" style="font-size:14px;">Invoice Number</td>
                    <td align="center" style="font-size:14px;">Due On or Before</td>
                    <td align="center" style="font-size:14px;">Total Due</td>
                    <td align="center" style="font-size:14px;">Amount Enclosed</td>
                </tr>
                <tr>
                    <td align="center" style="font-size:14px;">#{{$invoice->id}}</td>
                    <td align="center" style="font-size:14px;">{{$invoice->due_on->format("m/d/Y")}}</td>
                    <td align="center" style="font-size:14px;">${{moneyFormat($invoice->balance)}}</td>
                    <td align="center" style="font-size:14px;">&nbsp;</td>
                </tr>
            </table>
            <div style="margin-top: 10px;">
            </div>
            <div class="cutleft">
                <table style="border-collapse: collapse; text-align:center; font-size: 14px; " border="1" width="100%">
                    <tr style="background-color: #312c2c; color: white;">
                        <td align="center" style="font-size:14px;">Customer Info</td>
                    </tr>
                    <td style="font-size: 12px;">{{$invoice->account->name}}
                        <br/>
                        {{$invoice->account->phone}}<br/>
                        {{$invoice->account->admin->email}}
                    </td>
                </table>
            </div>
            <div class="cutright">
                <table style="border-collapse: collapse; text-align:center; font-size: 14px; " border="1" width="100%">
                    <tr style="background-color: #312c2c; color: white;">
                        <td align="center" style="font-size:14px;">Payments Payable To</td>
                    </tr>
                    <td style="font-size: 12px;">{{setting('brand.name')}}
                        <br/>
                        {{setting('brand.address')}}<br/>
                        {{setting('brand.csz')}}
                    </td>
                </table>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>

<div class="newpage"></div>

<div id="pageWrapper">
    <b>Summary of Invoice #{{$invoice->id}}</b>
    <br/>
    <table class="table" style="margin-top: 10px;" width="100%">
        <thead>
        <tr>
            <th>Item</th>
            <th>Price</th>
            <th>QTY</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($invoice->items as $product)
            <tr>
                <td>
                    <span class="bold" style="font-size:12px;">[{{$product->code}}] - {{$product->name}}</span>
                    <br/>
                    <small class="helper-text">{!! $product->description !!}</small>

                </td>
                <td align="center">${{moneyFormat($product->price)}}
                    @if($product->getCatalogPrice() > $product->price && setting('quote.showDiscount') != 'None')
                        <br/>
                        <span class="small" style="font-size:10px;"><del>${{moneyFormat($product->getCatalogPrice())}}</del>

                            </span>
                    @endif
                </td>
                <td align="center">{{$product->qty}}</a></td>
                <td align="center">${{moneyFormat($product->qty * $product->price)}}</td>
            </tr>
        @endforeach

        @if(setting('quotes.showDiscount') != 'None' && $invoice->discount > 0)
            <tr style="background: #393e44; color: #fff;">
                <td>&nbsp;</td>
                <td align="right"><span class="bold" style="font-size:14px;">Total Discount:</span></td>
                <td>&nbsp;</td>
                <td><span class="bold" style="font-size:14px;">${{moneyFormat($invoice->discount)}}</span>
                </td>
            </tr>
        @endif
        @if($invoice->tax > 0)
            <tr style="background: #393e44; color: #fff;">
                <td>&nbsp;</td>
                <td align="right"><span class="bold" style="font-size:14px;">Subtotal:</span></td>
                <td>&nbsp;</td>
                <td><span class="bold" style="font-size:14px;">${{moneyFormat($invoice->subtotal)}}</span>
                </td>
            </tr>
            <tr style="background: #393e44; color: #fff;">
                <td>&nbsp;</td>
                <td align="right"><span class="bold" style="font-size:14px;">Taxes:</span></td>
                <td>&nbsp;</td>
                <td><span class="bold" style="font-size:14px;">${{moneyFormat($invoice->tax)}}</span>
                </td>
            </tr>
        @endif

        <tr style="background: #393e44; color: #fff;">
            <td>&nbsp;</td>
            <td align="right"><span class="bold" style="font-size:14px;">Total:</span></td>
            <td>&nbsp;</td>
            <td><span class="bold" style="font-size:14px;">${{moneyFormat($invoice->total)}}</span>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        </tbody>
        @if($invoice->transactions->count())
            <thead>
            <tr>
                <th>Transaction</th>
                <th>Amount</th>
                <th></th>
                <th>Type</th>
            </tr>
            </thead>
            <tbody>
            @foreach($invoice->transactions as $trans)
                <tr>
                    <td align="center">{{$trans->local_transaction_id}}</td>
                    <td>${{moneyFormat($trans->amount)}}</td>
                    <td>&nbsp;</td>
                    <td>{{$trans->method}}</td>
                </tr>
            @endforeach
            </tbody>

        @endif
    </table>
</div>


<div class='footer'>
    {{setting('brand.name')}} Invoice #{{$invoice->id}} - Generated
    for {{$invoice->account->admin->name}}
    with {{$invoice->account->name}}
    <span class='pagenum'></span>
</div>
