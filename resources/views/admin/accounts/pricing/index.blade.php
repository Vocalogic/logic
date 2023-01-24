@extends('layouts.admin', ['title' => $account->name, 'crumbs' => [
    '/admin/accounts' => "Accounts",
    "/admin/accounts/$account->id" => $account->name,
    'Pricing'

]])
@section('content')
    <div class="row">
        <div class="col-lg-2 col-xs-12">
            @include('admin.accounts.submenu')
        </div>

        <div class="col-lg-10 col-xs-12">
            <h6 class="card-title"><b>Catalog Pricing</b></h6>
            <p class="card-text">
                There may be cases where you need to specify special pricing for customers on different products and
                services. Items entered here will automatically have their pricing changed (or advertised in the shop)
                for this customer only. Any quotes, account service items, etc will all have their default pricing set
                based on what you enter below.
            </p>

            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Monthly Services
                    <a class="live" data-title="Add Service Pricing" href="/admin/accounts/{{$account->id}}/pricing/service/add">
                        <i class="fa fa-plus"></i>
                    </a>
                    </h6>
                    @include('admin.accounts.pricing.list', ['service' => true])
                </div>
            </div>


            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="card-title">Products
                        <a class="live" data-title="Add Product Pricing" href="/admin/accounts/{{$account->id}}/pricing/product/add">
                            <i class="fa fa-plus"></i>
                        </a>
                        @include('admin.accounts.pricing.list', ['service' => false])
                    </h6>
                </div>
            </div>

        </div>
    </div>
@endsection
