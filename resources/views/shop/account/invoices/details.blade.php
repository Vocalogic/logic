<table class="table order-tab-table">
    <thead>
    <tr>
        <th>Item</th>
        <th>Price</th>
        <th>QTY</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($invoice->items as $item)
        <tr>
            <td style="text-align:left;"><strong class="theme-color">[{{$item->code}}] - {{$item->name}}</strong>
                <br/>
                <small>{!! nl2br($item->description)!!}</small>
            </td>
            <td>${{moneyFormat($item->price)}}</td>
            <td>{{$item->qty}}</td>
            <td>${{moneyFormat($item->qty * $item->price)}}</td>
        </tr>

    @endforeach
    </tbody>
</table>
