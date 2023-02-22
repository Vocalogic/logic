<table class="table table-striped">
    <thead>
    <tr>
        <th>#</th>
        <th>Account</th>
        <th>Created</th>
        <th>Total</th>
        <th>Balance</th>
    </tr>
    </thead>
    <tbody>
    @foreach(\App\Models\Invoice::with(['account','items', 'transactions'])->whereIn('status', [\App\Enums\Core\InvoiceStatus::DRAFT->value])->get() as $invoice)
        <tr>
            <td>
                <a href="/admin/invoices/{{$invoice->id}}"><span class="badge bg-primary">#{{$invoice->id}}</span></a>
            </td>
            <td><a href="/admin/accounts/{{$invoice->account->id}}">{{$invoice->account->name}}</a></td>
            <td>{{$invoice->created_at->format("m/d/y")}}</td>
            <td>${{moneyFormat($invoice->total)}}</td>
            <td>${{moneyFormat($invoice->balance)}}</td>
        </tr>
    @endforeach
    </tbody>

</table>
