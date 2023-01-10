<h3 class="mb-2">One-Time Purchases</h3>
<div class="cart-table mb-4">

    <div class="table-responsive-xl">
        <table class="table">
            <tbody>

            @foreach($products as $product)
                <tr class="product-box-contain">
                    <td class="product-detail">

                        <div class="product border-0">
                            <a href="/shop/{{$product->category->slug}}/{{$product->slug}}" class="product-image">
                                @if($product->photo_id && _file($product->photo_id)?->relative)
                                    <img src="{{_file($product->photo_id)->relative}}"
                                         class="img-fluid blur-up lazyload" alt="{{$product['name']}}">
                                @endif
                            </a>
                            <div class="product-detail">
                                <ul>
                                    <li class="name">
                                        <a href="/shop/{{$product->category->slug}}/{{$product->slug}}">{{$product->name}}
                                         @if($product->reservation_mode)
                                        <span class="badge bg-primary">Reservation</span>
                                         @endif
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="mt-2">
                            {{$product->description}}
                            @if($product->notes)
                                <br/><Br/><strong>{{$product->notes}}</strong>
                            @endif
                            <small>{!! $this->exportAddonText($product->uid) !!}</small>

                        </div>
                    </td>

                    <td class="price">
                        <h4 class="table-title text-content">Price</h4>
                        <h5>${{moneyFormat($product->price)}}
                            @if($product->price < $product->msrp && !$product->reservation_mode)
                                <del class="text-content">
                                    ${{moneyFormat($product->msrp)}}</del>
                            @endif
                        </h5>
                        @if($product->price < $product->msrp && !$product->reservation_mode)
                            <h6 class="theme-color">You Saved : ${{moneyFormat($product->msrp - $product->price)}}</h6>
                        @endif

                    </td>

                    <td class="quantity">
                        <h4 class="table-title text-content">Qty</h4>
                        <div class="quantity-price">
                            <div class="cart_qty">
                                @if($product->canUpdateQty)
                                <div class="input-group">
                                    <button type="button" class="btn qty-left-minus"
                                            data-type="minus" wire:click="decreaseItem('{{$product->uid}}')" data-field="">
                                        <i class="fa fa-minus ms-0" aria-hidden="true"></i>
                                    </button>
                                    <input class="form-control input-number qty-input" type="text"
                                           name="quantity" value="{{$product->qty}}">
                                    <button type="button" class="btn qty-right-plus"
                                            data-type="plus" wire:click="increaseItem('{{$product->uid}}')" data-field="">
                                        <i class="fa fa-plus ms-0" aria-hidden="true"></i>
                                    </button>
                                </div>
                                    @else
                                <h4>{{$product->qty}}</h4>
                                @endif

                            </div>
                        </div>
                    </td>

                    <td class="subtotal">
                        <h4 class="table-title text-content">Total (as configured)</h4>
                        <h5>${{moneyFormat($product->price * $product->qty + ($product->addonTotal * $product->qty))}}</h5>
                    </td>

                    <td class="save-remove">
                        @if($product->canUpdateQty)
                        <h4 class="table-title text-content">Action</h4>
                        <a class="remove close_button" href="javascript:void(0)" wire:click="removeItem('{{$product->uid}}')">Remove</a>
                        @endif
                    </td>
                </tr>
            @endforeach

            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td><b>One-Time Total:</b></td><td><h5>${{moneyFormat($productTotal)}}</h5></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
