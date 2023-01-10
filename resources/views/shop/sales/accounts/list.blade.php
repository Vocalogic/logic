<table class="table align-middle datatable table-striped">
    <thead>
    <tr>
        <th>Company</th>
        <th>MRR</th>
        <th>Commissionable</th>
        <th>Outstanding</th>
        <th>Next Bill Date</th>
    </tr>
    </thead>
    <tbody>
    @foreach($accounts as $account)
        <tr>
            <td class="d-flex align-items-center">
                <img
                    src="{{$account->logo_id ? _file($account->logo_id)->relative : "/icons/3024605.png"}}"
                    class="avatar img-fluid" alt="" width="50">

                <div class="ms-2 mb-0 fw-bold">{{$account->name}}</div>
            </td>

            <td>${{moneyFormat($account->mrr)}}</td>
            <td>${{moneyFormat($account->commissionable)}}</td>
            <td>${{moneyFormat($account->account_balance)}}</td>
            <td>{{$account->next_bill ? $account->next_bill->format("m/d/y") : "Not Set"}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<h4 class="text-center">Total Monthly Commissions: <b>${{moneyFormat(user()->totalCommission)}}</b></h4>
