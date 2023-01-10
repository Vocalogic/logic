<h3 class="mb-2">One-Time Product Purchases</h3>
<div class="cart-table mb-4">

    <div class="table-responsive-xl">
        <table class="table">
            <tbody>

            @foreach($quote->products as $product)

                <tr class="product-box-contain">
                    <td class="product-detail">

                        <div class="product border-0">
                            <a href="/shop/{{$product->item->category->slug}}/{{$product->item->slug}}"
                               class="product-image">
                                @if($product->item->photo_id)
                                    <img src="{{_file($product->item->photo_id)?->relative}}"
                                         class="img-fluid blur-up lazyload" alt="{{$product->item->name}}">
                                @endif
                            </a>
                            <div class="product-detail">
                                <ul>
                                    <li class="name">
                                        <a href="/shop/{{$product->item->category->slug}}/{{$product->item->slug}}">{{$product->item->name}}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="mt-2">
                            {{$product->description}}
                            @if($product->frequency)
                                <br/><b>Payment for this Item is being split and is shown in services above.</b>
                            @endif
                        </div>
                    </td>

                    <td class="price">
                        <h4 class="table-title text-content">Price</h4>
                        <h5>${{moneyFormat($product->price)}}</h5>
                        @if($product->saved > 0)
                            <h6 class="theme-color">You Save : ${{moneyFormat($product->saved)}}</h6>
                        @endif
                    </td>

                    <td class="quantity">
                        <h4 class="table-title text-content">Qty</h4>
                        <div class="quantity-price">
                            <div class="cart_qty">
                                @if(setting('quotes.modify') == 'Yes')

                                    <div class="input-group">
                                        <button type="button" class="btn qty-left-minus"
                                                data-type="minus" wire:click="decreaseItem({{$product->id}})"
                                                data-field="">
                                            <i class="fa fa-minus ms-0" aria-hidden="true"></i>
                                        </button>
                                        <input class="form-control input-number qty-input" type="text"
                                               name="quantity" value="{{$product->qty}}">
                                        <button type="button" class="btn qty-right-plus"
                                                data-type="plus" wire:click="increaseItem({{$product->id}})"
                                                data-field="">
                                            <i class="fa fa-plus ms-0" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                @else
                                    <h5>{{$product->qty}}</h5>
                                @endif
                            </div>
                        </div>
                    </td>

                    <td class="subtotal">
                        <h4 class="table-title text-content">Total</h4>
                        <h5>${{moneyFormat($product->price * $product->qty)}}</h5>
                    </td>

                    <td class="save-remove">
                        <h4 class="table-title text-content">Action</h4>
                        <a class="remove close_button" href="javascript:void(0)"
                           wire:click="removeItem({{$product->id}})">Remove</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
