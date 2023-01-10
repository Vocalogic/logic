<tr class="product-box-contain">
    <td class="product-detail">

        <div class="product border-0">
            <a href="/shop/{{$service->item->category->slug}}/{{$service->item->slug}}" class="product-image">
                @if($service->item->photo_id)
                    <img src="{{_file($service->item->photo_id)->relative}}"
                         class="img-fluid blur-up lazyload" alt="{{$service->item->name}}">
                @endif
            </a>
            <div class="product-detail">
                <ul>
                    <li class="name">
                        <a href="/shop/{{$service->item->category->slug}}/{{$service->item->slug}}">{{$service->item->name}}</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="mt-2">
            {{$service->description}}
           <Br/> <b>This temporary service will be invoiced a total of {{$service->payments}} times ({{$service->frequency->getHuman()}}) and will be removed automatically.</b>
        </div>
    </td>

    <td class="price">
        <h4 class="table-title text-content">Price</h4>
        <h5> ${{moneyFormat($service->frequency->splitTotal($service->qty * $service->price, $service->payments))}}</h5>

    </td>

    <td class="quantity">
        <h4 class="table-title text-content">Qty</h4>
        <div class="quantity-price">
            <div class="cart_qty">
                N/A
            </div>
        </div>
    </td>

    <td class="subtotal">
        <h4 class="table-title text-content">Total As Configured</h4>
    <h5>${{moneyFormat($service->frequency->splitTotal($service->qty * $service->price, $service->payments))}}</td>
    </h5>
    </td>

    <td class="save-remove">
        <h4 class="table-title text-content">Action</h4>
    </td>
</tr>
