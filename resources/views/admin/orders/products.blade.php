<table class="table table-sm table-striped">
    <thead>
    <tr>
        <th>Item</th>
        <th>Status</th>
        <th>Assigned</th>
        <th>Shipment</th>
        <th>Notes</th>
    </tr>
    </thead>
    <tbody>
    @foreach($order->items()->where('product', 1)->get() as $item)
        <tr>
            <td>
                <strong>[{{$item->code}}] {{$item->name}}</strong><br/>
                <small class="text-muted">{!! $item->description !!}</small>
            </td>
            <td>
                @if($item->status == 'Complete')
                    <span class="badge bg-success">Complete</span>
                @else
                    {{$item->status}}
                @endif
            </td>
            <td><a data-title="Assign Ownership" href="/admin/orders/{{$order->id}}/items/{{$item->id}}/assign"
                   class="live">{{$item->assigned ? $item->assigned->short : "Unassigned"}}</a>
                    @if(!$item->assigned)
                        <i class="text-danger fa fa-exclamation-circle"></i>
                    @endif
            </td>
            <td>
                @if($item->shipment)
                    <a href="/admin/shipments/{{$item->shipment->id}}">#{{$item->shipment->id}}</a>
                    <a href="/admin/orders/{{$order->id}}/items/{{$item->id}}/shipment"
                       data-title="Assign or Create new Shipment" class="live" data-position="right"><i class="fa fa-edit"></i></a>
                @else
                    <a href="/admin/orders/{{$order->id}}/items/{{$item->id}}/shipment"
                       data-title="Assign or Create new Shipment" class="live" data-position="right"><i class="fa fa-plus"></i></a>
                @endif
                @if($item->shippable && !$item->shipment)
                    <span class="badge bg-{{bm()}}warning"><i class="fa fa-exclamation"></i> Shipping Required</span>
                @endif


            </td>
            <td><a href="/admin/orders/{{$order->id}}/items/{{$item->id}}/notes" class="live" data-position="right"
                   data-title="{{$item->name}} Notes"><span
                        class="badge bg-{{bm()}}info">{{$item->notes()->count()}}</span></a></td>
        </tr>
    @endforeach
    </tbody>
</table>
