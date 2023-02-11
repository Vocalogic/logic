<table class="table table-sm table-striped">
    <thead>
    <tr>
        <th>Item</th>
        <th>Status</th>
        <th>Assigned</th>
        <th>Notes</th>
    </tr>
    </thead>
    <tbody>
    @foreach($order->items()->where('product', 0)->get() as $item)
        <tr>
            <td>
                <strong>[{{$item->code}}] {{$item->name}}</strong><br/>
                <small class="text-muted">{!! $item->description !!}</small>
            </td>
            <td>{{$item->status}}</td>
            <td><a data-title="Assign Ownership" href="/admin/orders/{{$order->id}}/items/{{$item->id}}/assign" class="live">{{$item->assigned ? $item->assigned->short : "Unassigned"}}</a>
                @if(!$item->assigned)
                    <i class="text-danger fa fa-exclamation-circle"></i>
                @endif
            </td>
            <td><a href="/admin/orders/{{$order->id}}/items/{{$item->id}}/notes" class="live" data-position="right" data-title="{{$item->name}} Notes"><span class="badge bg-{{bm()}}info">{{$item->notes()->count()}}</span></a></td>
        </tr>
    @endforeach

    </tbody>
</table>
