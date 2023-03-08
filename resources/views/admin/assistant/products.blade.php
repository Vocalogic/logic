<div class="card">
    <div class="card-body">
        <h6 class="card-title">Products in Cart <a data-title="Add Product to Cart" class="live" href="/admin/cart/{{$cart->get('id')}}/add/product"><i class="fa fa-plus"></i></a></h6>
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
                @foreach($cart->get('cart')->get('products') as $product)
                    <tr>
                        <td><strong>{{$product->name}}</strong> <a class='live' data-title="Edit {{$product->name}}" href="/admin/cart/{{$cart->get('id')}}/item/{{$product->uid}}"><i class="fa fa-edit"></i></a><br/>
                        <small class="text-muted">{{$product->description}}</small></td>
                        <td>{{$product->qty}}</td>
                        <td>${{moneyFormat($product->price)}}</td>
                        <td>${{moneyFormat($product->price * $product->qty)}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
