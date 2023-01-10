@include('pdf.pdf_styles')

<style>
    #header {
        height: 200px;
        color: #ffffff;
    }

    #customerInfo {
        height: 100px;

    }

    .segment {
        width: 50%;
        float: left;
    }

    .header-left {
        width: 50%;
        float: left;
        padding-left: 50px;
        padding-top: 20px;
        color: #0c0b0b;
    }

    .header-right {
        width: 50%;
        color: #0c0b0b;
        padding-top:30px;
        padding-left: 70px;
        float: left;
        font-size: 14px;
    }

    .content50 {
        width: 50%;
        float: left;
        padding: 10px;
    }

    .content80 {
        width: 60%;
        float: left;
        padding: 5px;
    }

    .content20 {
        width: 40%;
        float: left;
        padding: 5px;
    }

    .table tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    #quoteArea {
        margin-left: 50px;
        margin-right: 50px;
        margin-top: 10px;
    }

    .table {
        border-collapse: collapse;
        font-size: 12px;
        font-family: 'Nunito', sans-serif;
    }

    .table thead {
        background-color: #28323e;
        color: #fff;
        font-family: 'Nunito-Bold', sans-serif;

    }

    .secbg {
        background-color: #28323e !important;
        color: #fff;

    }

    .table thead th {
        padding: 10px;
    }


    .table tbody tr td {
        padding: 6px;
        font-size: 10px;
    }

    .helper-text {
        font-size: 10px;
        font-family: 'Nunito', sans-serif;
        position: relative;
        display: block;
        min-height: 18px;
        color: rgba(0, 0, 0, .54);
    }

    .helper-text::after {
        position: absolute;
        top: 0;
        left: 0;
        opacity: 1;
    }

    .product-left {
        float: left;
        width: 80%;
    }

    .product-right {
        float: left;
        width: 20%;
    }

    img {
        max-width: 200px;
        max-height: 200px;
    }

    .prodimg {
        max-width: 100px;
        max-height: 100px;
    }


</style>

<html>
@if(setting('brandImage.watermark'))
    @if($account->logo_id)
        <div id="watermark">
            <img src="{{_file($account->logo_id)->internal}}">
        </div>
    @else
        <div id="watermark"><img src="{{_file(setting('brandImage.watermark'))?->internal}}"></div>
    @endif
@endif


<div id="header">
    <div class="header-left">
        <img src="{{_file(setting('brandImage.light'))?->internal}}">
        <p>
            Statement For:
            <br/>
            <span class="bold" style="font-size:12px;">
            {{$account->admin->name}} with
            {{$account->name}}
        </span>
            <br/>

            {{$account->address}} @if($account->address2) {{$account->address2}} @endif<br/>
            {{$account->city}}, {{$account->state}} {{$account->postcode}}

        </p>
    </div>


    <div class="header-right">

        <table class="table">
            <tr>
                <td align="right"><span class="bold" style="font-size:12px;">Monthly Total: </span></td>
                <td>${{moneyFormat($account->mrr)}}</td>
            </tr>
        </table>

    </div>

</div>

<div class="clear"></div>


<div id="quoteArea">

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
        @foreach($account->itemsByCategory() as $group)

            <tr>
                <td colspan="4" align="center">
                    <h4><span class="bold">{{$group->name}}</span> - {{$group->description}}</h4>
                </td>
            </tr>

            @foreach($group->items as $product)
            <tr>
                <td style="border-left: 1px #000 dashed;">
                    @if ($product->item)
                        <span class="bold"
                              style="font-size:12px;">[{{$product->item->code}}] {{$product->item->name}}</span>
                        <br/>
                        <small class="helper-text">{{$product->description}}</small>

                    @else
                        <span class="bold" style="font-size:12px;">{{$product->name}}</span>
                    @endif
                        @if($product->notes)
                           <small class="helper-text" style="color: #152854">{!! nl2br($product->notes) !!}</small>
                        @endif
                </td>
                <td align="center">${{moneyFormat($product->price)}}</td>
                <td align="center">{{$product->qty}}</a></td>
                <td align="center">${{moneyFormat($product->qty * $product->price)}}</td>
            </tr>
        @endforeach
            @endforeach

        <tr style="background: #393e44; color: #fff;">
            <td>&nbsp;</td>
            <td><span class="bold">Total:</span></td>
            <td>&nbsp;</td>
            <td><span class="bold">${{moneyFormat($account->mrr)}}</span></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>


        </tbody>

    </table>


</div>


<div class='footer'>
    {{setting('brand.name')}} Account Statement for {{$account->name}} - THIS IS NOT A BILL
    <span class='pagenum'></span>
</div>


</html>
