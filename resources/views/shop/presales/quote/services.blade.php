<h3 class="mb-2">Monthly Services</h3>
<div class="cart-table mb-4">

    <div class="table-responsive-xl">
        <table class="table">
            <tbody>

            @foreach($quote->services as $service)

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
                    <small>{!! $service->addonSummary !!}</small>
                    </div>
                </td>

                <td class="price">
                    <h4 class="table-title text-content">Price</h4>
                    <h5>${{moneyFormat($service->price)}}/mo</h5>
                    @if($service->saved > 0)
                    <h6 class="theme-color">You Save : ${{moneyFormat($service->saved)}}/mo</h6>
                    @endif
                </td>

                <td class="quantity">
                    <h4 class="table-title text-content">Qty</h4>
                    <div class="quantity-price">
                        <div class="cart_qty">
                            @if(setting('quotes.modify') == 'Yes')
                            <div class="input-group">
                                <button type="button" class="btn qty-left-minus"
                                        data-type="minus" wire:click="decreaseItem({{$service->id}})" data-field="">
                                    <i class="fa fa-minus ms-0" aria-hidden="true"></i>
                                </button>
                                <input class="form-control input-number qty-input" type="text"
                                       name="quantity" value="{{$service->qty}}">
                                <button type="button" class="btn qty-right-plus"
                                        data-type="plus" wire:click="increaseItem({{$service->id}})" data-field="">
                                    <i class="fa fa-plus ms-0" aria-hidden="true"></i>
                                </button>
                            </div>
                                @else
                                <h5>{{$service->qty}}</h5>
                                @endif
                        </div>
                    </div>
                </td>

                <td class="subtotal">
                    <h4 class="table-title text-content">Total As Configured</h4>
                    <h5>${{moneyFormat($service->price * $service->qty + $service->addonTotal)}}</h5>
                </td>

                <td class="save-remove">
                    <h4 class="table-title text-content">Action</h4>
                    <a class="remove close_button" href="javascript:void(0)" wire:click="removeItem({{$service->id}})">Remove</a>
                </td>
            </tr>
          @endforeach
            @foreach($quote->items()->where('payments', '>', 0)->get() as $service)
                @include('shop.presales.quote.service_finance', ['service' => $service])
            @endforeach


            </tbody>
        </table>
    </div>
</div>
