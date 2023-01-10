@extends('layouts.shop.main', ['title' => "My Leads"])


@section('content')
    <section class="user-dashboard-section section-b-space">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-xxl-3 col-lg-4">
                    @include('shop.sales.menu')
                </div>
                <div class="col-xxl-9 col-lg-8">
                    <a class="btn btn-primary bg-primary btn-sm text-white w-25" href="/sales/leads/create">Create
                        Lead</a>
                    @include('shop.sales.leads.list')

                </div>
            </div>
        </div>
    </section>

@endsection
