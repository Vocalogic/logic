<table class="table table-striped">
    <thead>
    <tr>
        <th>Account</th>
        <th>Invoice #</th>
        <th>Payout</th>
        <th>Customer Payment Status</th>
        <th>Sent</th>
        <th>Due</th>
        <th>Paid</th>
    </tr>
    </thead>
    <tbody>
    @foreach($partner->getInvoices() as $invoice)
        @if(!$invoice->commissioned) @continue @endif
        <tr>
            <td>{{$invoice->account}}</td>
            <td>#{{$invoice->number}}</td>
            <td>${{moneyFormat($invoice->payout)}}</td>
            <td>{{$invoice->status}}</td>
            <td>{{$invoice->sent_on ? \Carbon\Carbon::createFromTimestamp($invoice->sent_on)->format("m/d/y") : "Not Sent"}}</td>
            <td>{{$invoice->due_on ? \Carbon\Carbon::createFromTimestamp($invoice->due_on)->format("m/d/y") : "Not Due"}}</td>
            <td>{{$invoice->paid_on ? \Carbon\Carbon::createFromTimestamp($invoice->paid_on)->format("m/d/y") : "Unpaid"}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
