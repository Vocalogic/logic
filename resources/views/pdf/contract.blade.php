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
        width: 40%;
        float: left;
        padding-left: 50px;
        padding-top: 20px;
        color: #0c0b0b;
    }

    .header-right {
        width: 50%;
        color: #0c0b0b;
        padding-top: 30px;
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

    #contentArea {
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
    @if($quote->lead && $quote->lead->logo_id)
        <div id="watermark">
            <img src="{{_file($quote->lead->logo_id)?->internal}}">
        </div>
    @elseif($quote->account && $quote->account->logo_id)
        <div id="watermark">
            <img src="{{_file($quote->account->logo_id)?->internal}}">
        </div>
    @else
        <div id="watermark"><img src="{{_file(setting('brandImage.watermark'))?->internal}}"></div>
    @endif
@endif


<div id="header">
    <div class="header-left">
        <img src="{{_file(setting('brandImage.light'))?->internal}}">

    </div>


    <div class="header-right" style="text-align: center;">

        <h3>Master Services Agreement</h3>
        <h4>Between</h4>
        <h3>{{setting('brand.name')}} and {{$quote->account->name}}</h3>


    </div>

</div>

<div class="clear"></div>

<div id="contentArea">
    {!! $quote->msaContent !!}

    @foreach($quote->getTOSArray() as $tos)
        <h3 style="text-align: center;">{{\App\Models\Term::find($tos)->name}}</h3>
        <p>
            {!! \App\Models\Term::find($tos)->convert([$quote]) !!}
        </p>
    @endforeach


    <p>
        IN WITNESS WHEREOF, the undersigned have caused this Master Services Agreement to be duly executed as of the Effective Date
    </p>
    <div class="sigBlock" style="margin-top: 50px;">
        <div class="left">
            <h3>{{$quote->account->name}}</h3>

            <h4>BY: {{$quote->contract_name}}</h4>
            <h4>IP: {{$quote->contract_ip}}</h4>
            <img src="{{_file($quote->signature_id)->internal}}">
        </div>
        <div class="right">
            <h3>{{setting('brand.name')}}</h3>
            This document was signed by {{$quote->contract_name}} and was automatically countersigned via
            the {{setting('brand.name')}} checkout process.
            <br/>
            <br/>
            This order was executed at {{$quote->activated_on->toDayDateTimeString()}}

        </div>
    </div>


</div>




<div class='footer'>
    {{setting('brand.name')}} Confidential - Contract #{{$quote->id}} executed by {{$quote->account->name}} on {{$quote->activated_on->format("M d, Y")}}
    <span class='pagenum'></span>
</div>



</html>
