@extends('layouts.admin', ['title' => $account->name, 'crumbs' => [
    '/admin/accounts' => "Accounts",
    $account->name,
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


            </div>
        </div>


            <div class="col-lg-3 col-xs-12">
                @livewire('admin.activity-component', ['account' => $account])
            </div>
        </div>
@endsection
