<div class="card">
    <div class="card-body">
        <h6 class="card-title">Services in Cart <a data-title="Add Service to Cart" class="live" href="/admin/cart/{{$cart->get('id')}}/add/service"><i class="fa fa-plus"></i></a></h6>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($cart->get('cart')->get('services') as $service)
                <tr>
                    <td><strong>{{$service->name}}</strong> <a class='live' data-title="Edit {{$service->name}}" href="/admin/cart/{{$cart->get('id')}}/item/{{$service->uid}}"><i class="fa fa-edit"></i></a><br/>
                        <small class="text-muted">{{$service->description}}</small></td>
                    <td>{{$service->qty}}</td>
                    <td>${{moneyFormat($service->price)}}</td>
                    <td>${{moneyFormat($service->price * $service->qty)}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
