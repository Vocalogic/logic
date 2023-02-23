@extends('layouts.admin', ['title' => $account->name, 'crumbs' => [
    '/admin/accounts' => "Accounts",
    "/admin/accounts/$account->id" => $account->name,
    'Services'

], 'log' => $account->logLink])
@section('content')
    <div class="row">
        <div class="col-xs-12 col-lg-2">
            @include('admin.accounts.submenu')
        </div>

        <div class="col-lg-10 col-xs-12">
            <div class="row">
                @if($account->items->count() == 0)

                    <div class="card">
                        <div class="card-body text-center p-5">
                            <img src="/icons/7486768.png" class="w120" alt="No Data">
                            <div class="mt-4 mb-3">
                                <span class="text-muted">No Monthly Services Found</span>
                            </div>
                            <a href="#recurringModal" data-bs-toggle="modal"
                               class="btn btn-primary border lift"><i class="fa fa-plus"></i> Add Service
                            </a>
                        </div>
                    </div>
                @else
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">


                        <table class="table table-striped">
                            <thead class="table-light">
                            <tr>
                                <th>Service</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($account->itemsByCategory() as $group)
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <h6 class="text-info mb-0"><b>{{$group->name}}</b></h6>
                                        <span class="small fs-7">{{$group->description}}</span>
                                    </td>
                                </tr>
                                @foreach($group->items as $item)
                                    @if($item->item)
                                        <tr class="border-start border-start-dashed">
                                            <td>
                                                <a data-title="Edit {{$item->item->name}}" class="live"
                                                   href="/admin/accounts/{{$account->id}}/services/{{$item->id}}"
                                                   data-title="{{$item->item->name}}">
                                                    <strong>[{{$item->item->code}}]
                                                        {{$item->item->name}}
                                                    </strong>
                                                </a>
                                                @if($item->item->addons->count())
                                                    <a data-bs-toggle='tooltip' class="live"
                                                       data-title="Manage Service Addons" title='Manage Service Addons'
                                                       href="/admin/accounts/{{$account->id}}/items/{{$item->id}}/addons">
                                                        <i class="fa fa-database"></i>
                                                    </a>
                                                @endif
                                                @if($item->item->meta->count())
                                                    <a class="live"
                                                       data-bs-toggle="tooltip"
                                                       data-title="Update Requirements"
                                                       title="Update Requirements"
                                                       href="/admin/accounts/{{$account->id}}/items/{{$item->id}}/meta">
                                                        <i class="fa fa-hdd-o"></i>
                                                    </a>
                                                @endif

                                                <br/><small class="text-muted fs-7">{{$item->description}}</small>
                                                @if($item->notes)
                                                    <br/>
                                                    <small class="text-primary">{!! nl2br($item->notes) !!}</small>
                                                @endif
                                                @if($item->item->meta->count())
                                                    <br/>
                                                    {!! $item->iterateMeta() !!}
                                                @endif

                                                @if($item->addons->count())
                                                    <br/>
                                                    @foreach($item->addons as $addon)
                                                        <small class="text-muted">&nbsp;&nbsp; -
                                                            <a class="confirm"
                                                               data-message="Are you sure you want to remove this addon?"
                                                               data-method="DELETE"
                                                               href="/admin/accounts/{{$account->id}}/items/{{$item->id}}/addons/{{$addon->id}}">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                            <strong>{{$addon->option->addon->name}}</strong>
                                                            : {{$addon->name}} x {{$addon->qty}}
                                                            (${{moneyFormat($addon->qty * $addon->price)}})</small>
                                                        <br/>
                                                    @endforeach
                                                @endif

                                                @if($item->remaining > 0)
                                                <span class="badge bg-primary"><i class="fa fa-clock-o"></i> {{$item->remaining}} payments left</span>
                                                @endif

                                            </td>
                                           <td>${{moneyFormat($item->price)}} <br/>{!! $item->variationDetail !!}</td>
                                            <td>{{$item->qty}}</td>
                                            <td><b>${{moneyFormat(($item->price * $item->qty) + $item->addonTotal)}}</b></td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach
                            <tr class="table-primary">
                                <td colspan="4" style="text-align:right">
                                    <strong class="text-primary">Monthly Total: </strong>${{moneyFormat($account->mrr)}}
                                </td>
                            </tr>
                            @if($account->commissionable && $account->partner)
                                <tr>
                                    <td colspan="4" style="text-align:right">
                                        <strong class="text-secondary">Commission to {{$account->partner->name}}:
                                        </strong>${{moneyFormat($account->commissionable)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" style="text-align:right">
                                        <strong class="text-primary">NET Monthly:
                                        </strong>${{moneyFormat($account->mrr - $account->commissionable)}}
                                    </td>
                                </tr>

                            @endif
                            </tbody>
                        </table>
                            </div>
                        </div>
                        <a href="#recurringModal" data-bs-toggle="modal" data-backdrop="false"
                           class="btn btn-primary"><i class="fa fa-plus"></i> add service</a>
                    </div>
                    <div class="col-lg-4 d-none d-lg-block">
                        @include('admin.accounts.services.bill_date')
                    </div>
                @endif
            </div>


        </div>
    </div>


    <x-modal name="recurringModal" size="xl" title="Add Monthly Service">
        <table class="table table-striped datatable">
            <thead>
            <tr>
                <th>Item</th>
                <th>Price</th>
            </tr>
            </thead>
            <tbody>
            @foreach(\App\Models\BillCategory::where('type', \App\Enums\Core\BillItemType::SERVICE)->get() as $cat)

                @foreach($cat->items as $item)
                    <tr>
                        <td>
                            <a href="/admin/accounts/{{$account->id}}/services/add/{{$item->id}}">
                                [{{$item->code}}] {{$item->name}}</a>
                            <br/>
                            <small class="text-muted">{{$item->category->name}}</small>
                        </td>
                        <td>${{moneyFormat($item->mrc)}}</td>
                    </tr>
                @endforeach
            @endforeach
            </tbody>
        </table>
    </x-modal>

@endsection
