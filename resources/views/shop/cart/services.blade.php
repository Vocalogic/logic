<h3 class="mb-2">Monthly Services</h3>
<div class="cart-table mb-4">

    <div class="table-responsive-xl">
        <table class="table">
            <tbody>

            @foreach($services as $service)
                <tr class="product-box-contain">
                    <td class="product-detail">
                        <div class="product border-0">
                            <a href="/shop/{{$service->category->slug}}/{{$service->slug}}" class="product-image">
                                @if($service->photo_id && _file($service->photo_id)?->relative)
                                    <img src="{{_file($service->photo_id)->relative}}"
                                         class="img-fluid blur-up lazyload" alt="{{$service->name}}">
                                @endif
                            </a>
                            <div class="product-detail">
                                <ul>
                                    <li class="name">
                                        <a href="/shop/{{$service->category->slug}}/{{$service->slug}}">{{$service->name}}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="mt-2">
                            {{$service->description}}
                            @if($service->notes)
                                <br/><Br/><strong>{{$service->notes}}</strong>
                            @endif
                            <small>{!! $this->exportAddonText($service->uid) !!}</small>
                        </div>
                    </td>

                    <td class="price">
                        <h4 class="table-title text-content">Price</h4>
                        <h5>${{moneyFormat($service->price)}}/mo
                            @if($service->price < $service->msrp)
                                <del class="text-content">
                                    ${{moneyFormat($service->msrp)}}</del>
                            @endif
                        </h5>
                        @if($service->price < $service->msrp)
                            <h6 class="theme-color">You Saved : ${{moneyFormat($service->msrp - $service->price)}}</h6>
                        @endif

                    </td>

                    <td class="quantity">
                        <h4 class="table-title text-content">Qty</h4>
                        <div class="quantity-price">
                            <div class="cart_qty">
                                @if($service->canUpdateQty)
                                    <div class="input-group">
                                        <button type="button" class="btn qty-left-minus"
                                                data-type="minus" wire:click="decreaseItem('{{$service->uid}}')"
                                                data-field="">
                                            <i class="fa fa-minus ms-0" aria-hidden="true"></i>
                                        </button>
                                        <input class="form-control input-number qty-input" type="text"
                                               name="quantity" value="{{$service->qty}}">
                                        <button type="button" class="btn qty-right-plus"
                                                data-type="plus" wire:click="increaseItem('{{$service->uid}}')"
                                                data-field="">
                                            <i class="fa fa-plus ms-0" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                @else
                                    <h4>{{$service->qty}}</h4>
                                @endif
                            </div>
                        </div>
                    </td>

                    <td class="subtotal">
                        <h4 class="table-title text-content">Total (as configured)</h4>
                        <h5>${{moneyFormat($service->price * $service->qty + ($service->addonTotal * $service->qty))}}</h5>
                    </td>

                    <td class="save-remove">
                        @if($service->canUpdateQty)
                            <h4 class="table-title text-content">Action</h4>
                            <a class="remove close_button" href="javascript:void(0)"
                               wire:click="removeItem('{{$service->uid}}')">Remove</a>
                        @endif
                    </td>
                </tr>
            @endforeach
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td><b>Monthly Total:</b></td>
                <td><h5>${{moneyFormat($serviceTotal)}}</h5></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
