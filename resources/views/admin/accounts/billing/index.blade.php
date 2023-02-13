@extends('layouts.admin', ['title' => $account->name, 'crumbs' => [
    '/admin/accounts' => "Accounts",
    "/admin/accounts/$account->id" => $account->name,
    'Billing'

], 'log' => $account->logLink])
@section('content')
    <div class="row">
        <div class="col-lg-2 col-xs-12">
            @include('admin.accounts.submenu')
        </div>

        <div class="col-lg-10 col-xs-12">
            @include('admin.accounts.billing.fields')
        </div>
    </div>
@endsection
@section('javascript')
    @if(getIntegration(\App\Enums\Core\IntegrationType::Merchant) == \App\Enums\Core\IntegrationRegistry::LogicPay)
        <script type="text/javascript" src="/assets/js/logicpay.js"></script>
    @endif
@endsection
