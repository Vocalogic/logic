@include('pdf.pdf_styles')
<style>



    #companyHeader {
        margin-top: 50px;
        height: 150px;
    }

    #shipToHeader {
        height: 200px;
    }

    #itemArea {

    }

    #notesArea {

    }

    .table {
        border-collapse: collapse;
        border: 1px #000 solid;
        width: 100%;

    }

    .table thead {
        font-family: Nunito-Bold;
        padding: 5px;
        text-align:center;
        background-color: #0f3d81;
        color: #fff;
    }

    .table td{
        padding: 10px;
    }


    .half {
        width: 50%;
    }

    .threeq {
        width: 75%;
    }

    .oneq {
        width: 25%;
    }




</style>
<div id="page">

    <div id="companyHeader">
        <div class="left twothirds">
            <img width="200" src="{{_file(setting('brandImage.light'))->internal}}">
            <p>This order has been placed with {{$order->vendor->name}}.</p>
            @if($order->ship_notes)
                <p><strong>NOTE: </strong> {{$order->ship_notes}}</p>
            @endif
        </div>
        <div class="left onethird">

            <div class="box">
                <div class="head" style="text-align:center;">
                    SHIPMENT NO.
                </div>
                <div class="body" style="text-align:center; font-size:12px;">
                    #{{$order->id}}
                </div>

            </div>
        </div>
        <div class="clear"></div>
    </div>

    <div id="shipToHeader">
        <div class="left half">
            <div class="box" style="width: 90%;">
                <div class="head">
                    ORDER FROM:
                </div>
                <div class="body">
                    <strong>{{setting('brand.name')}}</strong><br/>

                    {{setting('brand.address')}}<br/>
                    {{setting('brand.csz')}}<br/>


                </div>

            </div>
        </div>

        <div class="left half">
            <div class="box">
                <div class="head">
                    SHIP TO:
                </div>
                <div class="body">
                    <strong>{{$order->ship_company}}</strong><br/>
                    C/O: {{$order->ship_contact}}<br/>
                    {{$order->ship_address}}<br/>
                    @if($order->ship_address2)
                        {{$order->ship_address2}}<br/>
                    @endif
                    {{$order->ship_csz}}
                </div>

            </div>
        </div>
    </div>

    <div id="itemArea">

        <table class="table">
            <thead>
            <tr>
                <td>Product/Item</td>
                <td>QTY</td>
            </tr>
            </thead>
            <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td><strong>{{$item->qty}} x {{$item->name}}</strong>
                    </td>
                    <td style="text-align:center">
                        <strong>{{$item->qty}}</strong>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>


    </div>


    <div class="clear"></div>
    <div id="notesArea">
        <p style="text-align:justify; text-justify: inter-word; ">
            This order has been generated and sent from LogicCRM and has been manually authorized by {{setting('brand.name')}}.
            This document serves as authorization to bill/invoice the order and bill based on terms defined by {{$order->vendor->name}}.
        </p>
    </div>



</div>

<div class='footer'>
    {{setting('brand.name')}} Shipment #{{$order->id}} for {{$order->ship_company}}
    <span class='pagenum'></span>
</div>
