@extends('layouts.shop.main', ['title' => "My Accounts"])


@section('content')
    <section class="user-dashboard-section section-b-space">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-xxl-3 col-lg-4">
                    @include('shop.sales.menu')
                </div>
                <div class="col-xxl-9 col-lg-8">
                    @include('shop.sales.accounts.list')

                </div>
            </div>
        </div>
    </section>

@endsection
