@extends('layouts.admin', ['title' => "Active Accounts", 'crumbs' => [
     "Accounts",
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Active Accounts</h1>
            <small class="text-muted">Manage your active accounts</small>
        </div>

    </div> <!-- .row end -->

@endsection
@section('content')
    <div class="row">
        <div class="col-2">
            <a class="btn btn-outline-info live w-100 mb-4" data-title="Create new Account"
               href="/admin/accounts/create"
               type="button"><i class="fa fa-plus"></i> New Account
            </a>
            <div class="card">
                <div class="card-body">
                    <ul class="list-group list-group-custom">
                        <li class="list-group-item"><a class="color-600" href="/admin/accounts">Active Accounts
                                ({{\App\Models\Account::where('active', true)->where('id', '>', 1)->count()}})</a></li>
                        <li class="list-group-item"><a class="color-600" href="/admin/accounts?show=mrr">Monthly
                                Accounts ({{\App\Models\Account::whereHas('items')->where('active', true)->where('id', '>', 1)->count()}})</a></li>
                        <li class="list-group-item"><a class="color-600" href="/admin/accounts?show=nrc">One-Time (Non-MRR)
                                ({{\App\Models\Account::doesntHave('items')->where('active', true)->where('id', '>', 1)->count()}})</a></li>
                        <li class="list-group-item"><a class="color-600" href="/admin/accounts?show=inactive">Inactive
                                Accounts</a> ({{\App\Models\Account::where('active', false)->count()}})
                        </li>
                    </ul>
                </div>
            </div>
            <a class="btn btn-{{bm()}}secondary live w-100 btn-block mt-3" href="/admin/accounts/import/csv"
               data-title="Import Accounts into Logic">
                <i class="fa fa-recycle"></i> Import Accounts
            </a>
        </div>
        <div class="col-lg-10">
            @include('admin.accounts.list')
        </div>
    </div>
@endsection
