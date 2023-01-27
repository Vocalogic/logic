<div class="row">
    <div class="col-xs-12">
        <table class="table table-responsive table-striped table-sm">
            <thead>
            <tr class="small text-muted" style="background-color: #efefef">
                <th>Recurring Service</th>
                <th>Price</th>
                <th>QTY</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($quote->services()->orderBy('ord')->get() as $service)
                <tr class="small">
                    <td width="65%">
                    <b class="small">[{{$service->item->code}}] {{$service->item->name}}</b>
                        <br/>
                        <small class="helper-text">{{$service->description}}</small>
                        @if($service->notes)
                            <b>(<small class="text-muted">{{$service->notes}}</small>)</b>
                        @endif

                        @if($service->allowed_qty)
                            <small class="text-muted">{{$service->allowance}}</small>
                        @endif
                        @if($service->addons()->count())
                            @foreach($service->addons as $addon)
                                <br/><small class="text-muted">&nbsp;&nbsp; -
                                    <strong>{{$addon->option->addon->name}}</strong> : {{$addon->name}}
                                    x {{$addon->qty}} (${{moneyFormat($addon->qty * $addon->price,2)}})</small>
                            @endforeach
                        @endif

                        @if($service->iterateMeta(true))
                            {!! $service->iterateMeta(true) !!}
                        @endif

                    </td>
                    <td>
                        ${{moneyFormat($service->price,2)}}
                        @if($service->getCatalogPrice() > $service->price && setting('quote.showDiscount') != 'None')
                            <br/>
                            <span class="small text-muted fs-7"><del>${{moneyFormat($service->getCatalogPrice())}}</del>
                                (-{{$service->getDifferenceFromCatalog()}}%)
                            </span>
                        @endif
                    </td>
                    <td>
                           {{$service->qty}}</a></td>
                    <td>
                        <b>${{moneyFormat(($service->qty * $service->price) + $service->addonTotal,2)}}</b>
                    </td>
                </tr>
            @endforeach
            @foreach($quote->products as $service)
                @if($service->frequency && $service->payments)
                    <tr class="small">
                        <td width="65%">
                            <b class="small">[{{$service->item->code}}] {{$service->item->name}}</b>
                            <br/>
                            <small class="helper-text">{{$service->item->description}}</small>
                            <b class="small">
                                <div class="alert alert-info" style="padding: 3px;">
                                    <b>NOTE:</b> This temporary service will be invoiced a total of {{$service->payments}} times
                                ({{$service->frequency->getHuman()}}) and will be removed automatically.
                                    @if(setting('quotes.showFinanceCharge') == 'Yes')
                                        A monthly finance charge of {{$service->finance_charge}}% has been added.
                                    @endif
                                </div></b>
                        </td>
                        <td>
                            ${{moneyFormat($service->frequency->splitTotal($service->qty * $service->price, $service->payments, $service->finance_charge),2)}}</td>
                        <td>1</td>
                        <td>
                            ${{moneyFormat($service->frequency->splitTotal($service->qty * $service->price, $service->payments, $service->finance_charge),2)}}
                        </td>
                    </tr>
                @endif

            @endforeach

            <tr style="background-color: #3d3c3c;">
                <td colspan="3" align="right" style="color: #fff" class="small">
                    TOTAL RECURRING
                </td>
                <td style="color: #fff;" class="small">${{moneyFormat($quote->mrr)}}</td>
            </tr>

            </tbody>
        </table>
    </div>
</div>
