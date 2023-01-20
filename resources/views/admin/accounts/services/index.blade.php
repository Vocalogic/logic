@extends('layouts.admin', ['title' => $account->name, 'crumbs' => [
    '/admin/accounts' => "Accounts",
    "/admin/accounts/$account->id" => $account->name,
    'Services'

]])
@section('content')
    <div class="row">
        <div class="col-2">
            @include('admin.accounts.submenu')
        </div>

        <div class="col-lg-10">

            <div class="row">

                @if($account->items()->count() == 0)

                    <div class="card">
                        <div class="card-body text-center p-5">
                            <img src="/icons/7486768.png" class="w120" alt="No Data">
                            <div class="mt-4 mb-3">
                                <span class="text-muted">No Monthly Services Found</span>
                            </div>
                            <a href="#recurringModal" data-bs-toggle="modal"
                               class="btn btn-{{bm()}}primary border lift">Add Service</a>
                        </div>
                    </div>

                @else
                <div class="col-lg-8">

                    <table class="table table-striped table-sm mt-3">
                        <thead>
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
                                <td colspan="4">
                                    <h6 class="text-info">{{$group->name}} - {{$group->description}}</h6>
                                </td>
                            </tr>
                            @foreach($group->items as $item)
                                @if($item->item)
                                    <tr>
                                        <td><a data-title="Edit {{$item->item->name}}" class="live"
                                               href="/admin/accounts/{{$account->id}}/services/{{$item->id}}"
                                               data-title="{{$item->item->name}}">
                                                <strong>[{{$item->item->code}}
                                                    ] {{$item->item->name}}</strong></a> @if($item->item->addons()->count())
                                                <a data-bs-toggle='tooltip' class="live"
                                                   data-title="Manage Service Addons" title='Manage Service Addons'
                                                   href="/admin/accounts/{{$account->id}}/items/{{$item->id}}/addons"><i
                                                        class="fa fa-database"></i></a>
                                            @endif

                                            <br/><small class="text-muted">{{$item->description}}</small>
                                            @if($item->notes)
                                                <br/>
                                                <small class="text-primary">{!! nl2br($item->notes) !!}</small>
                                            @endif
                                            @if($item->item->meta()->count())
                                                <br/>
                                                {!! $item->iterateMeta() !!}
                                                <a class="live badge bg-primary"
                                                   data-title="Update Requirements"
                                                   href="/admin/accounts/{{$account->id}}/items/{{$item->id}}/meta">
                                                    <span class="small">edit requirements</span>
                                                </a>
                                            @endif

                                            @if($item->quote)
                                                <span
                                                    class="badge bg-{{bm()}}info">via quote #{{$item->quote->id}}</span>
                                            @endif
                                            @if($item->quote && $item->quote->contract_expires)
                                                <span class="badge bg-{{bm()}}primary">
                                            contracted until {{$item->quote->contract_expires->format('m/d/y')}}
                                        </span>
                                            @endif
                                            @if($item->frequency != \App\Enums\Core\BillFrequency::Monthly && $item->frequency)
                                                <span class="badge bg-{{bm()}}info">
                                            {{$item->frequency->getHuman()}} Billing (Bills: {{$item->next_bill_date ? $item->next_bill_date->format("m/d/y") : $account->next_bill->format("m/d/y")}})
                                        </span>
                                            @endif
                                            @if($item->allowed_qty)
                                                <br/>
                                                <small class='text-muted'>{{$item->allowance}}</small>
                                            @endif
                                            @if($item->remaining)
                                                <span class="badge bg-{{bm()}}primary">{{$item->remaining}} billing cycles left</span>
                                            @endif
                                            @if($item->terminate_on)
                                                <span class="badge bg-{{bm()}}danger">Terminating on {{$item->terminate_on->format("m/d/y")}} - {{$item->terminate_reason}}</span>
                                            @endif
                                            @if($item->suspend_on)
                                                <span class="badge bg-{{bm()}}warning">Suspending on {{$item->suspend_on->format("m/d/y")}} - {{$item->suspend_reason}}</span>
                                            @endif

                                            @if($item->requested_termination_date)
                                                <span class="badge bg-{{bm()}}warning">Customer Requested Termination on {{$item->requested_termination_date->format("m/d/y")}} - {{$item->requested_termination_reason}}</span>
                                            @endif


                                            @if($item->addons()->count())
                                                <br/>
                                                @foreach($item->addons as $addon)
                                                    <small class="text-muted">&nbsp;&nbsp; - <a class="confirm"
                                                                                                data-message="Are you sure you want to remove this addon?"
                                                                                                data-method="DELETE"
                                                                                                href="/admin/accounts/{{$account->id}}/items/{{$item->id}}/addons/{{$addon->id}}"><i
                                                                class="fa fa-trash"></i></a>
                                                        <strong>{{$addon->option->addon->name}}</strong>
                                                        : {{$addon->name}} x {{$addon->qty}}
                                                        (${{moneyFormat($addon->qty * $addon->price)}})</small>
                                                    <br/>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>${{moneyFormat($item->price)}}</td>
                                        <td>{{$item->qty}}</td>
                                        <td>${{moneyFormat(($item->price * $item->qty) + $item->addonTotal)}}</td>
                                    </tr>
                                @endif
                            @endforeach
                        @endforeach
                        <tr>
                            <td colspan="4" style="text-align:right">
                                <strong class="text-primary">Monthly Total: </strong>${{moneyFormat($account->mrr)}}
                            </td>
                        </tr>
                        @if($account->commissionable && $account->partner)
                            <tr>
                                <td colspan="4" style="text-align:right">
                                    <strong class="text-secondary">Commission to {{$account->partner->name}}
                                        : </strong>${{moneyFormat($account->commissionable)}}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" style="text-align:right">
                                    <strong class="text-primary">NET
                                        Monthly: </strong>${{moneyFormat($account->mrr - $account->commissionable)}}
                                </td>
                            </tr>

                        @endif
                        </tbody>
                    </table>
                    <a href="#recurringModal" data-bs-toggle="modal" data-backdrop="false"
                       class="btn btn-{{bm()}}primary"><i
                            class="fa fa-plus"></i> new service</a>


            </div>


            <div class="col-lg-4">
                @include('admin.accounts.services.bill_date')
            </div>
            @endif
        </div>


    </div>
    </div>


    <x-modal name="recurringModal" size="xl" title="Add Monthly Service">
        <table class="table datatable">
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
