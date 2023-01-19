<table class="table table-sm">
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
        @if(!$invoice->isPastDue)
            @continue
        @endif
        <tr>
            <td>
                <a href="/admin/accounts/{{$invoice->account->id}}/invoices/{{$invoice->id}}">
                    <span class="badge bg-{{bm()}}primary">#{{$invoice->id}}</span>
                </a>
                @if($invoice->recurring)
                    <a href="#" data-bs-toggle="tooltip" data-bs-placement="right" title="Monthly Invoice">
                        <i class="fa fa-refresh"></i>
                    </a>
                @endif
            </td>
            <td><a href="/admin/accounts/{{$invoice->account->id}}">{{$invoice->account->name}}</a></td>
            <td>{{$invoice->created_at->format("m/d/y")}}</td>
            <td>{{$invoice->due_on->format("m/d/y")}}</td>
            <td>${{moneyFormat($invoice->total)}}</td>
            <td>${{moneyFormat($invoice->balance)}}</td>
        </tr>
    @endforeach
    </tbody>

</table>
