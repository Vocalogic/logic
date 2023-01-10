<p>
    This commission is currently <b>{{$invoice->status}}</b>.
</p>

<table class="table table-sm table-striped">
    <thead>
    <tr>
        <th>Item</th>
        <th>Amount</th>
    </tr>
    </thead>
    <tbody>
    @foreach($invoice->items as $item)
        <tr>
            <td>{{$item->name}}</td>
            <td>${{moneyFormat($item->amount)}}</td>
        </tr>
    @endforeach
    <tr>
        <td align="right">Total Commission to be Paid:</td>
        <td>${{moneyFormat($invoice->total)}}</td>
    </tr>
    </tbody>
</table>
