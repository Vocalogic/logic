<table class="table table-sm datatable">
    <thead>
    <tr>
        <th>#</th>
        <th>Account</th>
        <th>Created</th>
        <th>Due</th>
        <th>Total</th>
        <th>Balance</th>
    </tr>
    </thead>
    <tbody>
        @foreach(\App\Models\Invoice::whereIn('status', [\App\Enums\Core\InvoiceStatus::PARTIAL->value, \App\Enums\Core\InvoiceStatus::SENT])->get() as $invoice)
            <tr>
                <td>
                    <a href="/admin/accounts/{{$invoice->account->id}}/invoices/{{$invoice->id}}"><span class="badge bg-{{bm()}}primary">#{{$invoice->id}}</span></a>
                </td>
                <td><a href="/admin/accounts/{{$invoice->account->id}}">{{$invoice->account->name}}</a></td>
                <td>{{$invoice->created_at->format("m/d/y")}}</td>
                <td>{{$invoice->due_on->format("m/d/y")}}
                    @if($invoice->isPastDue)
                        <span class="badge bg-{{bm()}}danger">past due</span>
                    @endif
                </td>
                <td>${{moneyFormat($invoice->total)}}</td>
                <td>${{moneyFormat($invoice->balance)}}</td>
            </tr>
        @endforeach
    </tbody>

</table>
