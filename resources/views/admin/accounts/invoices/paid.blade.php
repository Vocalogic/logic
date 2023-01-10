<table class="table table-striped">
    <thead>
    <tr>
        <th>#</th>
        <th>Paid On</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($account->invoices()->where('status', \App\Enums\Core\InvoiceStatus::PAID)->get() as $invoice)
        <tr>
            <td><a href="/admin/accounts/{{$account->id}}/invoices/{{$invoice->id}}"><span
                        class="badge bg-primary">#{{$invoice->id}}</span></a></td>
            <td>{{$invoice->paid_on?->format("m/d/y h:ia")}}</td>
            <td>${{moneyFormat($invoice->total)}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
