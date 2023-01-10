<table class="table table-striped">
    <thead>
    <tr>
        <th>Account</th>
        <th>Since</th>
        <th>MRR</th>
        <th>Next Bill</th>
        <th>Agent</th>
        <th>Commission</th>
    </tr>
    </thead>
    <tbody>
    @foreach($partner->getAccounts() as $account)
        <tr>
            <td>{{$account->name}}</td>
            <td>{{$account->created}}</td>
            <td>${{moneyFormat($account->mrr)}}</td>
            <td>{{$account->next_bill}}</td>
            <td>{{$account->agent}}</td>
            <td>
            <span class="badge bg-{{bm()}}primary">
                ${{number_format($account->commission,2)}}
            </span>
            </td>
        </tr>
    @endforeach
    </tbody>

</table>
