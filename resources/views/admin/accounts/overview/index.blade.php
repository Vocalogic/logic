@extends('layouts.admin', ['title' => $account->name, 'crumbs' => [
    '/admin/accounts' => "Accounts",
    $account->name,
    'Overview'

]])
@section('content')
    <div class="row">
        <div class="col-xs-12 col-lg-2">
            @include('admin.accounts.submenu')
        </div>
        <div class="col-xs-12 col-lg-10">
            <div class="row">
                <div class="col-lg-8">
                    <div class="lchart" id="monthlyMrr"
                         data-title="Monthly MRR (last 4 months)"
                         data-height="300"
                         data-url="/admin/graph/MRR_ACCOUNT?fn=getInvoicedMRRDiff&account={{$account->id}}&seriesType=timeSeries"
                         data-xtype="datetime"
                         data-type="line"
                         data-y="Total MRR"
                         data-disable-toolbar="true"
                         data-wait="Getting Historical MRR...">
                    </div>
                </div>
                <div class="col-lg-4">
                    @livewire('admin.activity-component', ['account' => $account])
                </div>
            </div>
        </div>
    </div>
@endsection
