<h3 class="mb-2 mt-2">Monthly Services</h3>
<form method="POST" action="/sales/leads/{{$lead->id}}/quotes/{{$quote->id}}">
    @csrf
    @method('PUT')
    <div class="cart-table mb-4">
        <div class="table-responsive-xl">
            <table class="table">
                <tbody>

                @foreach($quote->services as $service)
                    <tr class="product-box-contain">
                        <td class="product-detail" width="50%">
                            <div class="product border-0">
                                <a href="/shop/{{$service->item->category->slug}}/{{$service->item->slug}}"
                                   class="product-image">
                                    @if($service->item->photo_id && _file($service->item->photo_id)?->relative)
                                        <img src="{{_file($service->item->photo_id)->relative}}"
                                             class="img-fluid blur-up lazyload" alt="{{$service->item->name}}">
                                    @endif
                                </a>
                                <div class="product-detail">
                                    <ul>
                                        <li class="name">
                                            <a href="/shop/{{$service->item->category->slug}}/{{$service->item->slug}}">{{$service->item->name}}</a>
                                            <a class="confirm" data-method="DELETE"
                                               href="/sales/leads/{{$lead->id}}/quotes/{{$quote->id}}/item/{{$service->id}}"
                                               data-message="Are you sure you want to remove this item from the quote?"><i
                                                    class="fa fa-trash"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="mt-2">
                                {{$service->description}}
                                @if($service->item->min_price)
                                    <br/>
                                    <small class="text-muted">Min: ${{moneyFormat($service->item->min_price)}}</small>
                                @endif
                                @if($service->item->max_price)
                                    <br/>
                                    <small class="text-muted">Max: ${{moneyFormat($service->item->max_price)}}</small>
                                @endif
                                @if($service->notes)
                                    <br/><Br/><strong>{{$service->notes}}</strong>
                                @endif
                            </div>
                        </td>

                        <td class="price">
                            <h4 class="table-title text-content">Price</h4>

                            <h5>
                                <input class="form-control" type="text"
                                       name="p_{{$service->id}}" value="{{moneyFormat($service->price)}}">


                                @if($service->price < $service->item->msrp)
                                    <del class="text-content">
                                        ${{moneyFormat($service->item->msrp)}}</del>
                                @endif
                            </h5>
                            @if($service->price < $service->item->msrp)
                                <h6 class="theme-color">Customer Saved :
                                    ${{moneyFormat($service->item->msrp - $service->price)}}</h6>
                            @endif

                        </td>

                        <td class="quantity">
                            <h4 class="table-title text-content">Qty</h4>

                            <div class="cart_qty">
                                <input class="form-control" type="text"
                                       name="q_{{$service->id}}" value="{{$service->qty}}">

                            </div>

                        </td>

                        <td class="subtotal">
                            <h4 class="table-title text-content">Total (as configured)</h4>
                            <h5>
                                ${{moneyFormat($service->price * $service->qty + ($service->item->addonTotal * $service->qty))}}</h5>
                        </td>
                        <td>
                            <h4 class="table-title text-content">Commission</h4>
                            <h6 class="text-primary">${{moneyFormat($service->commissionable)}}/mo</h6>

                        </td>


                    </tr>
                @endforeach
                <tr>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;</td>
                    <td align="right"><b>Monthly Total:</b></td>
                    <td><h5>${{moneyFormat($quote->mrr)}}</h5></td>
                    <td class="text-primary">${{moneyFormat($quote->commissionable)}}/mo</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>


    <h3 class="mb-2 mt-4">One-Time Purchases</h3>



    <div class="cart-table mb-4">
        <div class="table-responsive-xl">
            <table class="table">
                <tbody>

                @foreach($quote->products as $service)
                    <tr class="product-box-contain">
                        <td class="product-detail" width="50%">
                            <div class="product border-0">
                                <a href="/shop/{{$service->item->category->slug}}/{{$service->item->slug}}"
                                   class="product-image">
                                    @if($service->item->photo_id && _file($service->item->photo_id)?->relative)
                                        <img src="{{_file($service->item->photo_id)->relative}}"
                                             class="img-fluid blur-up lazyload" alt="{{$service->item->name}}">
                                    @endif
                                </a>
                                <div class="product-detail">
                                    <ul>
                                        <li class="name">
                                            <a href="/shop/{{$service->item->category->slug}}/{{$service->item->slug}}">{{$service->item->name}}</a>
                                            <a class="confirm" data-method="DELETE"
                                               href="/sales/leads/{{$lead->id}}/quotes/{{$quote->id}}/item/{{$service->id}}"
                                               data-message="Are you sure you want to remove this item from the quote?"><i
                                                    class="fa fa-trash"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="mt-2">
                                {{$service->description}}
                                @if($service->notes)
                                    <br/><Br/><strong>{{$service->notes}}</strong>
                                @endif
                            </div>
                        </td>

                        <td class="price">
                            <h4 class="table-title text-content">Price</h4>
                            <h5>
                                <input class="form-control" type="text"
                                       name="p_{{$service->id}}" value="{{moneyFormat($service->price)}}">

                                @if($service->price < $service->item->msrp)
                                    <del class="text-content">
                                        ${{moneyFormat($service->item->msrp)}}</del>
                                @endif
                            </h5>
                            @if($service->price < $service->item->msrp)
                                <h6 class="theme-color">Customer Saved :
                                    ${{moneyFormat($service->item->msrp - $service->price)}}</h6>
                            @endif

                        </td>

                        <td class="quantity">
                            <h4 class="table-title text-content">Qty</h4>

                            <div class="cart_qty">
                                <input class="form-control" type="text"
                                       name="q_{{$service->id}}" value="{{$service->qty}}">

                            </div>

                        </td>

                        <td class="subtotal">
                            <h4 class="table-title text-content">Total (as configured)</h4>
                            <h5>
                                ${{moneyFormat($service->price * $service->qty + ($service->item->addonTotal * $service->qty))}}</h5>
                        </td>


                    </tr>
                @endforeach
                <tr>
                    <td>&nbsp; </td>
                    <td>&nbsp;</td>
                    <td align="right"><b>One-Time Total:</b></td>
                    <td><h5>${{moneyFormat($quote->nrc)}}</h5></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-lg-6">
            <h3>Total Due upon Signing: <b>${{moneyFormat($quote->total)}}</b></h3>

        </div>
        <div class="col-lg-6">
            <div class="form-floating theme-form-floating">
                {!! Form::select('term', \App\Models\Quote::getTermSelectable(), $quote->term, ['class' => 'form-control']) !!}
                <label for="address">Select Term</label>
                <span class="helper-text">Select the contract term for the quote</span>
            </div>

            <div class="form-floating theme-form-floating mt-3">
                <input type="text" class="form-control" name="name" value="{{$quote->name}}">
                <label for="address">Quote Name</label>
                <span class="helper-text">Enter a name for this quote</span>
            </div>

        </div>
    </div>





    <input type="submit" class="btn btn-primary bg-primary btn-sm w-25 text-white" value="Save Quote">

</form>
