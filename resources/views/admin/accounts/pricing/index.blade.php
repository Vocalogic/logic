@extends('layouts.admin', ['title' => $account->name, 'crumbs' => [
    '/admin/accounts' => "Accounts",
    "/admin/accounts/$account->id" => $account->name,
    'Pricing'

]])
@section('content')
    <div class="row">
        <div class="col-2">
            @include('admin.accounts.submenu')
        </div>

        <div class="col-lg-10">
            Incomplete : This section will provide specific pricing for customers to be able to order their own
            hardware, and for partners to quote directly out of logic to end customers if enabled.

        </div>
    </div>
@endsection
