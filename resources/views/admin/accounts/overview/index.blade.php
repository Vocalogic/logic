@extends('layouts.admin', ['title' => $account->name, 'crumbs' => [
    '/admin/accounts' => "Accounts",
    "/admin/accounts/$account->id" => $account->name,
    'Overview'

], 'log' => $account->logLink])
@section('content')
    <div class="row">
        <div class="col-xs-12 col-lg-2">
            @include('admin.accounts.submenu')
        </div>
        <div class="col-xs-12 col-lg-7">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="lchart" id="monthlyMrr"
                             data-title="Monthly MRR (last 4 months)"
                             data-height="300"
                             data-url="/admin/graph/MRR_ACCOUNT?fn=getInvoicedMRRDiff&account={{$account->id}}&seriesType=timeSeries&months=4"
                             data-xtype="datetime"
                             data-type="line"
                             data-y="Total MRR"
                             data-disable-toolbar="true"
                             data-wait="Getting Historical MRR...">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">


                <div class="col-lg-4">
                    <div class="card">
                        <div class="lchart" id="mrrRank"
                             data-title="Account Percentage Global MRR"
                             data-height="300"
                             data-url="/admin/graph/MRR_ACCOUNT?fn=getAccountRankMRR&account={{$account->id}}&seriesType=radialBar&months=4"
                             data-type="radialBar"
                             data-y="Total MRR"
                             data-disable-toolbar="true"
                             data-wait="Getting Account Ranking...">
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="lchart" id="ttlRank"
                             data-title="Account Percentage Total Invoiced"
                             data-height="300"
                             data-url="/admin/graph/MRR_ACCOUNT?fn=getAccountRankTotal&account={{$account->id}}&seriesType=radialBar&months=4"
                             data-type="radialBar"
                             data-y="Total Invoiced"
                             data-disable-toolbar="true"
                             data-wait="Getting Account Ranking...">
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">


                    <div class="card mt-3 mb-3 p-3 border-dark">
                        <div class="d-flex align-items-center">
                            <div class="avatar rounded-circle no-thumbnail bg-light"><i
                                    class="fa fa-calendar fa-lg"></i></div>
                            <div class="flex-fill ms-3 text-truncate">
                                <div class="small">Generally Pays In</div>
                                <span class="h5 mb-0">{{$account->paysIn}} days</span>
                            </div>
                        </div>
                    </div>

                    @if($account->payment_method == \App\Enums\Core\PaymentMethod::CreditCard && !$account->merchant_payment_token)
                        <div class="card mt-3 mb-3 p-3 border-danger">
                            <div class="d-flex align-items-center">
                                <div class="avatar rounded-circle no-thumbnail"><i
                                        class="fa fa-credit-card text-danger fa-lg"></i></div>
                                <div class="flex-fill ms-3 text-truncate">
                                    <div class="small">Payment Method (Credit Card)</div>
                                    <span class="h5 mb-0">No card on file</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($account->declined)
                        <div class="card mt-3 mb-3 p-3 border-danger">
                            <div class="d-flex align-items-center">
                                <div class="avatar rounded-circle no-thumbnail"><i
                                        class="fa fa-credit-card text-danger fa-lg"></i></div>
                                <div class="flex-fill ms-3 text-truncate">
                                    <div class="small">Account in Declined State</div>
                                    <span class="h5 mb-0">Credit card declined recently</span>
                                </div>
                            </div>
                        </div>
                    @endif


                    @if($account->account_balance)
                        <div class="card mt-3 mb-3 p-3 border-dark">
                            <div class="d-flex align-items-center">
                                <div class="avatar rounded-circle no-thumbnail bg-light"><i
                                        class="fa fa-dollar fa-lg"></i></div>
                                <div class="flex-fill ms-3 text-truncate">
                                    <div class="small">Outstanding Balance</div>
                                    <span class="h5 mb-0">${{moneyFormat($account->account_balance)}}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($account->parent)
                        <div class="card mt-3 mb-3 p-3 border-warning">
                            <div class="d-flex align-items-center">
                                <div class="avatar rounded-circle no-thumbnail bg-light"><i
                                        class="fa fa-building-o fa-lg"></i></div>
                                <div class="flex-fill ms-3 text-truncate">
                                    <div class="small">Parent Account</div>
                                    <span class="h6 mb-0"><a
                                            href="/admin/accounts/{{$account->parent->id}}">{{$account->parent->name}}</a></span>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($account->account_credit)
                        <div class="card mb-3 p-3 border-dark">
                            <div class="d-flex align-items-center">
                                <div class="avatar rounded-circle no-thumbnail bg-light"><i
                                        class="fa fa-exclamation fa-lg"></i></div>
                                <div class="flex-fill ms-3 text-truncate">
                                    <div class="small">Credit Balance</div>
                                    <span class="h5 mb-0">${{number_format(abs($account->account_credit),2)}}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>


            </div>
        </div>


        <div class="col-lg-3 col-xs-12">
            @livewire('admin.activity-component', ['account' => $account])
        </div>
    </div>
@endsection
