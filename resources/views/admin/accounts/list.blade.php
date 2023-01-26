<div class="card mt-2">
    <div class="card-body">
        <table class="table align-middle datatable table-striped table-sm">
            <thead>
            <tr>
                <th>Company</th>
                <th>Agent</th>
                <th>MRR</th>
                <th>Outstanding</th>
                <th>Next Bill</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($accounts as $account)
                <tr>
                    <td class="d-flex align-items-center">
                        <img
                            src="{{$account->logo_id ? _file($account->logo_id)->relative : "/icons/3024605.png"}}"
                            class="avatar" alt="">
                        <a href="/admin/accounts/{{$account->id}}">
                            <div class="ms-2 mb-0 fw-bold">{{$account->name}}
                        </a>
                    </td>
                    <td>{{$account->agent ? $account->agent->short : "None"}}</td>
                    <td>${{moneyFormat($account->mrr)}}

                    </td>
                    <td>${{moneyFormat($account->account_balance,2)}}</td>
                    <td>{{$account->next_bill ? $account->next_bill->format("m/d/y") : "Not Set"}}</td>
                    <td>
                        @if($account->declined)
                            <span class="badge bg-danger"><i class="fa fa-exclamation"></i> declined</span>
                        @endif
                        @if (hasIntegration(\App\Enums\Core\IntegrationType::Finance) && !$account->finance_customer_id)
                            <span class="badge bg-danger" data-bs-toggle="tooltip" title="Accounting Integration Error"><i class="fa fa-exclamation-circle"></i></span>
                        @endif
                        @if($account->auto_bill)
                            <span class="badge bg-success" data-bs-toggle="tooltip" title="Auto-Pay Enabled">
                                <i class="fa fa-dollar"></i>
                            </span>
                            @endif

                        @if($account->partner)
                                <a href="/admin/partners/{{$account->partner->id}}">
                                    <span class="badge bg-info" data-bs-toggle="tooltip" title="Partner: {{$account->partner->name}}"><i class="fa fa-compass"></i></span>
                                </a>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
