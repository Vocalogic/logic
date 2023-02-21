<tr>
    <td class="d-flex align-items-center">
        <img
            src="{{$obj->logo_id ? _file($obj->logo_id)->relative : "/icons/3024605.png"}}"
            class="avatar" alt="">
        <a href="/admin/accounts/{{$obj->id}}">
            <div class="ms-2 mb-0 fw-bold">{{$obj->name}}
        </a>
    </td>
    <td>{{$obj->agent ? $obj->agent->short : "None"}}</td>
    <td>${{moneyFormat($obj->mrr)}}

    </td>
    <td>${{moneyFormat($obj->account_balance,2)}}</td>
    <td>{{$obj->next_bill ? $obj->next_bill->format("m/d/y") : "Not Set"}}</td>
    <td>
        @if($obj->declined)
            <span class="badge bg-danger"><i class="fa fa-exclamation"></i> declined</span>
        @endif
        @if (hasIntegration(\App\Enums\Core\IntegrationType::Finance) && !$obj->finance_customer_id)
            <span class="badge bg-danger" data-bs-toggle="tooltip" title="Accounting Integration Error"><i
                    class="fa fa-exclamation-circle"></i></span>
        @endif
        @if($obj->auto_bill)
            <span class="badge bg-success" data-bs-toggle="tooltip" title="Auto-Pay Enabled">
                                <i class="fa fa-dollar"></i>
                            </span>
        @endif
        @if($obj->payment_method == \App\Enums\Core\PaymentMethod::CreditCard && !$obj->merchant_payment_token)
            <span class="badge bg-warning" data-bs-toggle="tooltip" title="No credit card on file">
                                <i class="fa fa-credit-card"></i>
                            </span>
        @endif

        @if($obj->partner)
            <a href="/admin/partners/{{$obj->partner->id}}">
                                <span class="badge bg-info" data-bs-toggle="tooltip"
                                      title="Partner: {{$obj->partner->name}}"><i class="fa fa-compass"></i></span>
            </a>
        @endif
    </td>
</tr>
