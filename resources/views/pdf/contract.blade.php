<style>{!! file_get_contents(public_path() . "/assets/oldbs/dist/css/bootstrap.css") !!}</style>


<style>
    @font-face {
        font-family: 'Nunito';
        font-style: normal;
        font-display: auto;
        src: url({{storage_path()}}/fonts/Nunito-Regular.ttf);
    }


    @font-face {
        font-family: 'Nunito-Bold';
        font-style: normal;
        font-display: auto;
        src: url({{storage_path()}}/fonts/Nunito-Bold.ttf);
    }

    #watermark {
        position: fixed;
        top: 45%;
        width: 100%;
        text-align: center;
        font-size: 120px;
        opacity: .08;
        transform: rotate(30deg);
        transform-origin: 50% 50%;
        z-index: -1000;
    }

    .footer {
        position: fixed;
        left: 25%;
        bottom: 10px;
        padding-top: 25px;
        text-align: center;
        font-size: 10px;
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

<div class="container">
    <div class="row mb-3">
        <div class="col-xs-6">
            <img class="img-fluid" style="max-height: 200px; max-width : 200px"
                 src="{{_file(setting('brandImage.dark'))?->internal}}">
        </div>

        <div class="col-xs-6">


        </div>
    </div>


    <div class="row mt-5">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-body mt-3">
                    <p style="font-size: 16px; text-align:center;"><strong>{{setting('brand.name')}} Master Services
                            Agreement</strong></p>
                    <p class="text-justify">
                        {!! $quote->msaContent !!}
                    </p>
                </div>
            </div>

        </div>
    </div>


    <div class="row mt-3">
        <div class="col-xs-12">
            @foreach($quote->getTOSArray() as $tos)
                <p style="text-align: center; font-size:16px;"><strong>{{\App\Models\Term::find($tos)->name}}</strong>
                </p>
                <p class="text-justify">
                    {!! \App\Models\Term::find($tos)->convert([$quote]) !!}
                </p>
            @endforeach
        </div>
    </div>


    <div class="row mt-3">
        <div class="col-xs-12">
            <p class="text-justify">
                IN WITNESS WHEREOF, the undersigned have caused this Master Services Agreement to be duly executed as of
                {{$quote->activated_on->format("M d, Y")}}
            </p>
        </div>
    </div>

    <div class="row mt-3">

        <div class="col-xs-6">
            <p style="font-size: 18px;">{{$quote->account->name}}</p>
            <img class="img-fluid" style="max-width: 400px;" src="{{_file($quote->signature_id)->internal}}">
            <hr/>
            <p class="font-size: 16px;">By: {{$quote->contract_name}} - {{$quote->contract_ip}}</p>

        </div>

        <div class="col-xs-5">
            <p class="font-size: 18px;"{{setting('brand.name')}}</p>
            <p>
                This document was signed by {{$quote->contract_name}} and was automatically countersigned via
                the {{setting('brand.name')}} checkout process.
            </p>

        </div>

    </div>


</div>


<div class='footer'>
    {{setting('brand.name')}} Confidential - Contract #{{$quote->id}} executed by {{$quote->account->name}}
    on {{$quote->activated_on->format("M d, Y")}}
    <span class='pagenum'></span>
</div>


</html>
