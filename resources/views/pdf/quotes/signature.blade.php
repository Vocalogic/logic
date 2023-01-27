<div class="row">
    <div class="col-xs-6">
        <div class="panel panel-default small">
            <div class="panel-heading">
                <div class="panel-body">
                    <div class="text-center">
                    This quote was executed on <b>{{$quote->activated_on->format("m/d/y h:ia")}}</b> by
                    {{$quote->contract_name}} from {{$quote->contract_ip}}
                    </div>
                    <img src="{{_file($quote->signature_id)->internal}}">
                </div>
            </div>
        </div>
    </div>
</div>
