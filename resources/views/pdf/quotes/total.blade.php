<div class="row">
    <div class="col-xs-12">
        <table class="table table-responsive table-sm small table-striped">
            <tbody>
            @if($quote->mrr > 0)
            <tr>
                <td align="right"><b>Recurring Total:</b></td>
                <td width="20%">${{moneyFormat($quote->mrr)}}</td>
            </tr>
            @endif
            @if($quote->nrc > 0)
            <tr>
                <td align="right"><b>One-Time Total:</b></td>
                <td>${{moneyFormat($quote->nrc)}}</td>
            </tr>
            @endif
            @if($quote->tax > 0)
            <tr>
                <td align="right"><b>Quote Subtotal:</b></td>
                <td>${{moneyFormat($quote->subtotal)}}</td>
            </tr>
            <tr>
                <td align="right"><b>Tax:</b></td>
                <td>${{moneyFormat($quote->tax)}}</td>
            </tr>
            @endif
            <tr>
                <td align="right"><b>Quote Total:</b></td>
                <td>${{moneyFormat($quote->total)}}</td>
            </tr>
            <tr>
                <td align="right"><b>Contract-Term:</b></td>
                <td>{{$quote->term ? $quote->term . " months" : "Month-To-Month"}}</td>
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
