<div class="card mt-2">
    <div class="card-body">
        <table class="table align-middle datatable table-striped">
            <thead>
            <tr>
                <th>Company</th>
                <th>Agent</th>
                <th>MRR</th>
                <th>Outstanding</th>
                <th>Next Bill</th>
                @if(\App\Models\Partner::count() > 0)
                    <th>Partner</th>
                @endif

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
                        <br/>
                        @if($account->declined)
                            <span class="badge bg-{{bm()}}danger"><i class="fa fa-exclamation"></i> declined</span>
                        @endif

                        @if (hasIntegration(\App\Enums\Core\IntegrationType::Finance) && !$account->finance_customer_id)
                            <span class="badge bg-{{bm()}}danger">integration error</span>
                        @endif
                        @if($account->auto_bill)
                            <span class="badge bg-{{bm()}}success"><i class="fa fa-check"></i>auto-pay</span>
                        @endif
                    </td>
                    <td>{{$account->agent ? $account->agent->short : "None"}}</td>
                    <td>${{moneyFormat($account->mrr)}}

                    </td>
                    <td>${{moneyFormat($account->account_balance,2)}}</td>
                    <td>{{$account->next_bill ? $account->next_bill->format("m/d/y") : "Not Set"}}</td>
                    @if(\App\Models\Partner::count() > 0)
                        <td>{!! $account->partner ? "<a href='/admin/partners/{$account->partner->id}'>{$account->partner->name}</a>" : "Internal" !!}</td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
