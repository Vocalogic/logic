<div class="card mt-3">
    <div class="card-body">
        <table class="table table-striped table-sm small">
            <thead>
            <tr>
                <th>#</th>
                <th>Order #</th>
                <th>Account</th>
                <th>Status</th>
                <th>Tracking</th>
                <th>Submitted</th>
                <th>Shipped</th>
                <th>Arriving</th>
            </tr>
            </thead>
            <tbody>
            @foreach(\App\Models\Shipment::where('active', true)->get() as $shipment)
                <tr>
                    <td><a href="/admin/shipments/{{$shipment->id}}"><span class="badge bg-{{bm()}}primary">#{{$shipment->id}}</span></td>
                    <td><a href="/admin/orders/{{$shipment->order->id}}">#{{$shipment->order->id}}</td>
                    <td><a href="/admin/accounts/{{$shipment->order->account->id}}">{{$shipment->order->account->name}}</a></td>
                    <td>{{$shipment->status->value}}</td>
                    <td>{{$shipment->tracking ?: "N/A"}}</td>
                    <td>{{$shipment->submitted_on ? $shipment->submitted_on->format("m/d/y") : "Unsubmitted"}}</td>
                    <td>{{$shipment->shipped_on ? $shipment->shipped_on->format("m/d/y") : "Not Shipped"}}</td>
                    <td>{{$shipment->expected_arrival ? $shipment->expected_arrival->format("m/d/y") : "Unknown"}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
