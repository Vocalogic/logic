@include('pdf.pdf_styles')
<style>
    #companyHeader {
        margin-top: 50px;
        height: 125px;


    }

    #declaration {

        margin-top: 25px;
        height: 275px;
    }

    #losingdetails {

        margin-top: 25px;
        height: 135px;
    }

    #inventory {

        margin-top: 25px;
        height: 125px;
    }

    #signature {

        margin-top: 25px;
        height: 195px;
    }


    .center-headline {
        text-align: center;
        font-family: Nunito-Bold;
        font-size: 16px;
    }


</style>
<html>
@if(setting('brandImage.watermark'))
    <div id="watermark"><img src="{{_file(setting('brandImage.watermark'))->internal}}">
    </div>
@endif


<div id="page">

    <div id="companyHeader">
        <div class="left twothirds">
            <img width="200" src="{{_file(setting('brandImage.light'))->internal}}">
            <p>This LOA has been completed by {{$lnp->p_contact}}
                @if($lnp->provider) for {{$lnp->provider->name}} @endif on behalf
                of {{$lnp->p_company}}.</p>
        </div>
        <div class="left onethird">

            <div class="box">
                <div class="head" style="text-align:center;">
                    LNP Order NO.
                </div>
                <div class="body" style="text-align:center; font-size:12px;">
                    #{{$lnp->id}}
                </div>

            </div>
        </div>
        <div class="clear"></div>
    </div>

    <div id="declaration">
        <div class="center-headline">LETTER OF AUTHORIZATION</div>
        <p>
            ATTN: {{$lnp->p_contact}} ON BEHALF OF {{$lnp->p_company}}
        </p>
        <p>
            Thank you for choosing {{setting('brand.name')}} as your new service provider. In order to transition
            your current telephone service we must work with your previous service provider to ensure that your
            number is transferred and service uninterrupted. This request could take up to forty five (45) days
            to complete depending on your current service provider's technical limitations.
        </p>
        <p>
            Your current service provider requires this letter as proof that you have explicitly authorized and
            requested that your service and current <strong>billing telephone number</strong> (BTN) to be
            transferred to another service provider. By signing this form you provide us with the authorization
            required to initiate the process of transferring your telephonen umber(s) to {{setting('brand.name')}}.
        </p>
        <p>
            If there are any rejections on this request by your current service provider, we may require a recent
            bill as proof of ownership.
        </p>


    </div>


    <div id="losingdetails">
        <div class="center-headline">LOSING CARRIER DETAILS</div>

        <table style="width:95%">
            <tr>
                <td colspan="2">
                    <strong>Losing Carrier: </strong> {{$lnp->p_provider}}
                </td>
                <td>
                    <strong>BTN/ATN: </strong> {{$lnp->p_btn}}
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <strong>Company Name: </strong> {{$lnp->p_company}}
                </td>
                <td>
                    <strong>Authorizing Contact: </strong> {{$lnp->p_contact}}
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <strong>Service Address: </strong> {{$lnp->p_address}} {{$lnp->p_address2}}
                </td>
            </tr>
            <tr>
                <td><strong>City:</strong> {{$lnp->p_city}}</td>
                <td><strong>State:</strong> {{$lnp->p_state}}</td>
                <td><strong>Zip:</strong> {{$lnp->p_zip}}</td>
            </tr>


        </table>

    </div>

    <div id="inventory">
        <div class="center-headline">PORTING NUMBER INVENTORY</div>
        <p>
            The following numbers are to be ported @if($lnp->provider) to {{$lnp->provider->name}}@endif.
        </p>
        <p><strong>{!! implode(", ", $lnp->inventory) !!}</strong></p>

    </div>

    <div id="signature">
        <div class="center-headline">AUTHORIZING SIGNATURE</div>
        <p>
            By signing below, I designate {{setting('brand.name')}} as my new service provider, and authorize the
            transfer of the numbers listed above so that {{setting('brand.name')}} may provide the applicable services.
            I also authorize {{setting('brand.name')}} to obtain customer service records when required by carriers to
            provide services.
        </p>

        <table width="95%">
            <tr>
                <td width="50%">
                    <strong>Signature:</strong>
                    {!! \App\Operations\Core\Signature::renderImage($lnp->p_signature) !!}
                </td>
                <td>
                    <strong>Authorizing Name:</strong> {{$lnp->p_contact}}
                    <br/>
                    <strong>Date Signed:</strong> {{$lnp->signed_on->format("m/d/y")}}
                </td>
            </tr>
        </table>
    </div>
</div>


<div class='footer'>
    {{setting('brand.name')}} LNP Order #{{$lnp->id}} ({{$lnp->hash}})
    <span class='pagenum'></span>
</div>


</html>


