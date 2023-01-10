<table class="table table-sm datatable">
    <thead>
    <tr>
        <th>#</th>
        <th>Account</th>
        <th>Created</th>
        <th>Paid On</th>
        <th>Total</th>
        <th>Balance</th>
    </tr>
    </thead>
    <tbody>
    @foreach(\App\Models\Invoice::whereIn('status', [\App\Enums\Core\InvoiceStatus::PAID->value])->get() as $invoice)
        <tr>
            <td>
                <a href="/admin/accounts/{{$invoice->account->id}}/invoices/{{$invoice->id}}"><span class="badge bg-{{bm()}}primary">#{{$invoice->id}}</span></a>
            </td>
            <td><a href="/admin/accounts/{{$invoice->account->id}}">{{$invoice->account->name}}</a></td>
            <td>{{$invoice->created_at->format("m/d/y")}}</td>
            <td>{{$invoice->paid_on->format("m/d/y")}}</td>
            <td>${{moneyFormat($invoice->total)}}</td>
            <td>${{moneyFormat($invoice->balance)}}</td>
        </tr>
    @endforeach
    </tbody>

</table>
