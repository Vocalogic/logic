<tr>
    <td>{{$obj->id}}</td>
    <td>{{$obj->created_at->format("Y-m-d")}}</td>
    <td><a href="/admin/invoices/{{$obj->invoice->id}}">#{{$obj->invoice->id}}</a></td>
    <td><a href="/admin/accounts/{{$obj->account->id}}">{{$obj->account->name}}</a></td>
    <td>${{moneyFormat($obj->amount)}}
        @if($obj->remote_transaction_id)<br/><small class="text-muted">{{$obj->remote_transaction_id}}</small>@endif</td>
    <td>${{moneyFormat($obj->fee)}}</td>
    <td>${{moneyFormat($obj->net)}}</td>
    <td>{{$obj->method}}</td>
</tr>
