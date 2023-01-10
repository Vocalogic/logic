<div class="card">


    <div class="card-body">


        <h5 class="card-title">Recurring Items
            <a href="#newRecurring" data-bs-toggle="modal"><i class="fa fa-plus"></i></a>
        </h5>
        <table class="table">
            <thead>
            <tr>
                <th></th>
                <th width="75%">Item</th>
                <th>Price</th>
                <th>QTY</th>
                <th>Ext. Price</th>

            </tr>
            </thead>
            <tbody>
            @forelse($quote->services()->orderBy('ord')->get() as $service)
                <tr>
                    <td>@if($service->item->photo_id)
                            <img src="{{_file($service->item->photo_id)?->relative}}" width="100">
                        @endif
                    </td>
                    <td>
                        @if($service->canMoveDown())
                            <a data-bs-toggle='tooltip' data-bs-placement='left' title='Move Item Down'
                               href="/admin/quotes/{{$quote->id}}/items/{{$service->id}}/move/down">
                                <i class="fa fa-arrow-down small"></i></a>
                        @endif
                        @if($service->canMoveUp())
                            <a data-bs-toggle='tooltip' data-bs-placement='left' title='Move Item Up'
                               href="/admin/quotes/{{$quote->id}}/items/{{$service->id}}/move/up">
                                <i class="fa fa-arrow-up small"></i></a>
                        @endif

                        <a data-title="Edit {{$service->item->name}}"
                           class="live"
                           href="/admin/quotes/{{$quote->id}}/items/{{$service->id}}">
                            [{{$service->item->code}}] {{$service->item->name}}
                        </a>



                        @if($service->item->addons()->count())
                            <a class='live' data-bs-toggle='tooltip' data-title="Manage Service Addons"
                               title='Manage Service Addons'
                               href="/admin/quotes/{{$quote->id}}/items/{{$service->id}}/addons"><i
                                    class="fa fa-database"></i></a>
                        @endif
                        <br/>
                        <small class="text-muted">{{$service->description}}</small>
                        @if($service->allowed_qty)
                            <br/> <small class="text-muted">{{$service->allowance}}</small>
                        @endif
                        @if($service->item->meta()->count())
                            <br/>
                            {!! $service->iterateMeta() !!}
                            <a class="live"
                               data-title="Update Requirements"
                               href="/admin/quotes/{{$quote->id}}/items/{{$service->id}}/meta">
                                <span class="small">edit requirements</span>
                            </a>
                        @endif
                        @if($service->addons()->count())
                            <br/>
                            @foreach($service->addons as $addon)
                                <small class="text-muted">&nbsp;&nbsp; -
                                    <strong>{{$addon->option->addon->name}}</strong> : {{$addon->name}}
                                    x {{$addon->qty}} (${{moneyFormat($addon->qty * $addon->price,2)}})</small> <br/>
                            @endforeach
                        @endif
                        @if($service->frequency && $service->frequency != \App\Enums\Core\BillFrequency::Monthly)
                            <br/>
                            <span class="badge bg-{{bm()}}primary">{{$service->frequency->getHuman()}} Billing
                                        </span>
                        @endif
                    </td>
                    <td>${{moneyFormat($service->price,2)}}</a></td>
                    <td>{{$service->qty}} <a class="confirm"
                                             data-message="Are you sure you want to remove {{$service->item->name}}?"
                                             href="/admin/quotes/{{$quote->id}}/del/{{$service->id}}"
                                             data-method="DELETE">
                            <i class="fa fa-trash"></i></a></a>
                    </td>
                    <td>${{moneyFormat(($service->qty * $service->price) + $service->addonTotal,2)}}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">
                        <div class="card mt-3">
                            <div class="card-body text-center">
                                <img src="/assets/images/no-data.svg" class="w120" alt="No Data">
                                <div class="mt-4 mb-3">
                                    <span class="text-muted">No Recurring Items Found</span>

                                </div>
                                <a class="btn btn-{{bm()}}primary border lift" data-bs-toggle="modal"
                                   href="#newRecurring"><i class="fa fa-plus"></i> Add
                                    Recurring Service</a>

                                <a class="btn btn-{{bm()}}secondary border lift" data-bs-toggle="modal"
                                   href="#recurringCopy"><i class="fa fa-copy"></i> Copy From Quote</a>

                            </div>
                        </div>
                    </td>
                </tr>
            @endforelse
            <tr>
                <td colspan="5" align="right">Total Recurring: <strong>${{moneyFormat($quote->mrr)}}</strong></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="card mt-3">
    <div class="card-body">


        <h5 class="card-title">Non-Recurring Items <a href="#oneModal" data-bs-toggle="modal"> <i
                    class="fa fa-plus"></i></a></h5>
        <table class="table">
            <thead>
            <tr>
                <th></th>
                <th width="75%">Item</th>
                <th>Price</th>
                <th>QTY</th>
                <th>Ext. Price</th>

            </tr>
            </thead>
            <tbody>
            @forelse($quote->products()->orderBy('ord')->get() as $product)
                <tr>
                    <td>@if($product->item->photo_id)
                            <img src="{{_file($product->item->photo_id)?->relative}}" width="100">
                        @endif
                    </td>
                    <td>
                        @if($product->canMoveDown())
                            <a data-bs-toggle='tooltip' data-bs-placement='left' title='Move Item Down'
                               href="/admin/quotes/{{$quote->id}}/items/{{$product->id}}/move/down">
                                <i class="fa fa-arrow-down small"></i></a>
                        @endif
                        @if($product->canMoveUp())
                            <a data-bs-toggle='tooltip' data-bs-placement='left' title='Move Item Up'
                               href="/admin/quotes/{{$quote->id}}/items/{{$product->id}}/move/up">
                                <i class="fa fa-arrow-up small"></i></a>
                        @endif
                        <a data-title="Update {{$product->item->name}}" class="live"
                           href="/admin/quotes/{{$quote->id}}/items/{{$product->id}}">
                            [{{$product->item->code}}] {{$product->item->name}} @if($product->item->addons()->count())
                                <a data-bs-toggle='tooltip' class="live" data-title="Manage Product Addons"
                                   title='Manage Product Addons'
                                   href="/admin/quotes/{{$quote->id}}/items/{{$product->id}}/addons"><i
                                        class="fa fa-database"></i></a>
                            @endif
                        </a>
                        <br/>
                        <small class="text-muted">{{$product->description}}</small>
                        @if($product->addons()->count())
                            <br/>
                            @foreach($product->addons as $addon)
                                <small class="text-muted">&nbsp;&nbsp; -
                                    <strong>{{$addon->option->addon->name}}</strong> : {{$addon->name}}
                                    x {{$addon->qty}} (${{moneyFormat($addon->qty * $addon->price,2)}})</small> <br/>
                            @endforeach
                        @endif

                            @if($product->item->meta()->count())
                                <br/>
                                {!! $product->iterateMeta() !!}
                                <a class="live"
                                   data-title="Update Requirements"
                                   href="/admin/quotes/{{$quote->id}}/items/{{$product->id}}/meta">
                                    <span class="small">edit requirements</span>
                                </a>
                            @endif

                        @if($product->frequency)
                            <br/> <span class="badge bg-{{bm()}}primary">{{$product->frequency->getHuman()}}
                                        financing ({{$product->payments}} payments @
                                        ${{moneyFormat($product->frequency->splitTotal($product->qty * $product->price, $product->payments),2)}} p/{{$product->frequency->getHumanShort()}})</span>
                        @endif
                        <br/>
                    </td>
                    <td>${{moneyFormat($product->price,2)}}</a></td>
                    <td>{{$product->qty}} <a class="confirm"
                                             data-message="Are you sure you want to remove {{$product->item->name}}?"
                                             href="/admin/quotes/{{$quote->id}}/del/{{$product->id}}"
                                             data-method="DELETE">
                            <i class="fa fa-trash"></i></a></a>
                    </td>
                    <td>${{moneyFormat(($product->qty * $product->price) + $product->addonTotal,2)}}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        <div class="card mt-3">
                            <div class="card-body text-center">
                                <img src="/assets/images/no-data.svg" class="w120" alt="No Data">
                                <div class="mt-4 mb-3">
                                    <span class="text-muted">No One-Time Items Found</span>

                                </div>
                                <a class="btn btn-{{bm()}}primary border lift" data-bs-toggle="modal"
                                   href="#oneModal"><i class="fa fa-plus"></i> Add
                                    One-time Item</a>
                                <a class="btn btn-{{bm()}}secondary border lift" data-bs-toggle="modal"
                                   href="#oneCopy"><i class="fa fa-copy"></i> Copy From Quote</a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforelse
            <tr>
                <td colspan="5" align="right">Total Non-Recurring: <strong>${{moneyFormat($quote->nrc)}}</strong>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
