<div class="row">
    <div class="col-xs-12">
        <table class="table table-responsive table-sm small">
            <tbody>
                <tr>
                    <td><b>Recurring Total:</b></td> <td>${{moneyFormat($quote->mrr,2)}}</td>
                    <td><b>One-Time Total:</b></td><td>${{moneyFormat($quote->nrc,2)}}</td>
                    <td><b>Total:</b></td><td>${{moneyFormat($quote->total,2)}}</td>
                    <td><b>Contract-Term:</b></td><td>{{$quote->term ? $quote->term . " months" : "Month-To-Month"}}</td>
                </tr>
                @if($quote->notes)
                    <tr>
                        <td colspan="8">{!! nl2br($quote->notes) !!}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
