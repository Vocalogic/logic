<style>{!! file_get_contents(public_path() . "/assets/oldbs/dist/css/bootstrap.css") !!}</style>


<style>
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
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-3">
            <img class="img-responsive" src="{{_file(setting('brandImage.dark'))?->internal}}">
        </div>
        <div class="col-xs-4">
            @include('pdf.quotes.header')
        </div>

        <div class="col-xs-4">
            <div class="panel panel-default small">
                <div class="panel-heading">
                    <b class="small">QUOTE #{{$quote->id}}</b>
                </div>
                <div class="panel-body" style="height: 70px;">
                    {{$quote->lead ? $quote->lead->contact : $quote->account->admin->name}}
                    <br/>
                    {{$quote->lead ? $quote->lead->company : $quote->account->name}}
                    <Br/>
                    @if($quote->lead && $quote->lead->address)
                        {{$quote->lead->address}} @if($quote->lead->address2)
                            {{$quote->lead->address2}}
                        @endif<br/>
                        {{$quote->lead->city}}, {{$quote->lead->state}} {{$quote->lead->zip}}
                    @endif
                    @if($quote->account)
                        {{$quote->account->address}} @if($quote->account->address2)
                            {{$quote->account->address2}}
                        @endif<br/>
                        {{$quote->account->city}}, {{$quote->account->state}} {{$quote->account->postcode}}
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>


<div class="container-fluid">


    <div class="row" style="margin-top:0px; padding-top:0px;">


        @if($quote->services()->count())
            @include('pdf.quotes.services')
        @endif

        @if($quote->products()->count())
            @include('pdf.quotes.products')
        @endif

        @include('pdf.quotes.total')

        @if($quote->signature_id)
            @include('pdf.quotes.signature')
        @endif

    </div>

</div>


</html>
