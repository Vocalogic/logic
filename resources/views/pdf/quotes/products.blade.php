<div class="row">
    <div class="col-xs-12">
        <table class="table table-responsive table-striped table-sm">

            <thead>
            <tr class="small text-muted" style="background-color: #efefef">
                <th>One-Time Item</th>
                <th>Price</th>
                <th>QTY</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($quote->products()->orderBy('ord')->get() as $product)
                <tr class="small">
                    <td width="65%">
                            <b class="small">[{{$product->item->code}}] {{$product->item->name}}</b>
                        <br/>
                        <small class="helper-text">{{$product->description}}
                            @if($product->notes)
                                <b>(<small class="text-muted">{{$product->notes}}</small>)</b>
                            @endif
                        </small>


                        @if($product->addons()->count())
                            @foreach($product->addons as $addon)
                                <br/> <small class="text-muted">&nbsp;&nbsp; -
                                    <strong>{{$addon->option->addon->name}}</strong> : {{$addon->name}}
                                    x {{$addon->qty}} (${{moneyFormat($addon->qty * $addon->price,2)}})</small>
                            @endforeach
                        @endif


                    </td>
                    <td>${{moneyFormat($product->price,2)}}
                        @if($product->getCatalogPrice() > $product->price && setting('quote.showDiscount') != 'None')
                            <br/>
                            <span class="small text-muted fs-7"><del>${{moneyFormat($product->getCatalogPrice())}}</del>
                                (-{{$product->getDifferenceFromCatalog()}}%)
                            </span>
                        @endif
                    </td>
                    <td>{{$product->qty}}</a></td>
                    <td>
                        <b>${{moneyFormat(($product->qty * $product->price) + $product->addonTotal,2)}}</b>
                    </td>
                </tr>
            @endforeach

            <tr style="background-color: #3d3c3c;">
                <td colspan="3" align="right" style="color: #fff" class="small">
                    TOTAL ONE-TIME
                </td>
                <td style="color: #fff;" class="small">${{moneyFormat($quote->nrc)}}</td>
            </tr>

            </tbody>
        </table>
    </div>
</div>
