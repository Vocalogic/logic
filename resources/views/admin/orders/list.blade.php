<div class="card mb-3">
    <div class="card-body">
        <table class="table table-striped table-sm small">
            <thead>
            <tr>
                <th>#</th>
                <th>Account</th>
                <th>Invoice</th>
                <td>Items</td>
                <th>Status</th>
                @if(hasIntegration(\App\Enums\Core\IntegrationType::Support))
                    <th>Ticket #</th>
                @endif
            </tr>
            </thead>
            <tbody>
            @foreach(\App\Models\Order::where('active', true)->get() as $order)
                <tr>
                    <td>
                        <a href="/admin/orders/{{$order->id}}" class="badge bg-{{bm()}}primary">#{{$order->id}}</a></span>
                    </td>
                    <td><a href="/admin/accounts/{{$order->account->id}}">{{$order->account->name}}</a><br/>
                        <small class="text-muted">{{$order->name}}</small>
                    @foreach($order->shipments as $ship)
                        <span class="badge bg-{{bm()}}info"><a class='text-white' href="/admin/shipments/{{$ship->id}}">Shipment #{{$ship->id}} to {{$ship->vendor->name}}</a></span>
                    @endforeach
                    </td>
                    <td>
                        @if($order->invoice)
                        <a href="/admin/invoices/{{$order->invoice->id}}">#{{$order->invoice->id}}</a>
                        @endif
                    </td>
                    <td>{{$order->items()->count()}}</td>
                    <td>{{$order->status}}</td>
                    @if(hasIntegration(\App\Enums\Core\IntegrationType::Support))
                        @if($order->ticket_id)
                            <td>
                                <a target="_blank"
                                   href="{{(new \App\Operations\Integrations\Support\Support())->getTicketUrl($order->ticket_id)}}">
                                    {{$order->ticket_id}}
                                </a>
                            </td>
                        @else
                            <td>
                                N/A
                            </td>
                        @endif
                    @endif

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
