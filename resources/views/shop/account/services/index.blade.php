@extends('layouts.shop.main', ['title' => "Active Services", 'crumbs' => [
     "/shop" => "Home",
     "/shop/account" => auth()->user()->account->name,
     "Active Services"
]])

@section('content')
    <section class="user-dashboard-section section-b-space">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-xxl-3 col-lg-4">
                    @include('shop.account.menu')
                </div>

                <div class="col-xxl-9 col-lg-8">

                    <div class="dashboard-right-sidebar">
                        <div class="dashboard-wishlist">

                            <div class="title">
                                <h2>{{$account->name}} Monthly Services</h2>
                                <span class="title-leaf title-leaf-gray">
                                            <svg class="icon-width bg-gray">
                                                <use xlink:href="/ec/assets/svg/leaf.svg#leaf"></use>
                                            </svg>
                                        </span>
                            </div>
                            @include('shop.account.services.list')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
