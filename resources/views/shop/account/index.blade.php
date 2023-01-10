@extends('layouts.shop.main', ['title' => "My Account", 'crumbs' => [
     "/shop" => "Home",
     auth()->user()->account->name
]])

@section('content')
    <section class="user-dashboard-section section-b-space">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-xxl-3 col-lg-4">
                    @include('shop.account.menu')
                </div>

                <div class="col-xxl-9 col-lg-8">
                    @if($account->admin && !$account->admin->email_verified_at)
                        <div class="alert alert-danger mb-3">
                            Your email address has not been verified and orders will not be processed until this is
                            completed. Please check your email for more information.
                        </div>
                    @endif


                    <button class="btn left-dashboard-show btn-animation btn-md fw-bold d-block mb-4 d-lg-none">Show
                        Menu
                    </button>
                    <div class="dashboard-right-sidebar">
                        <div class="dashboard-home">
                            <div class="title">
                                <h2>{{$account->name}}</h2>
                                <span class="title-leaf">
                                            <svg class="icon-width bg-gray">
                                                <use xlink:href="/ec/assets/svg/leaf.svg#leaf"></use>
                                            </svg>
                                        </span>
                            </div>

                            <div class="dashboard-user-name">
                                <h6 class="text-content">Hi <b class="text-title">{{user()->first}}</b>, </h6>
                                <p class="text-content">
                                    Review your account, download invoices and order new services on-demand. If you
                                    require assistance, please enter your message in the box to the right and an
                                    agent will be notified of your note.
                                </p>
                            </div>

                            <div class="row g-4 mt-4">
                                <div class="col-xxl-6 col-lg-12 col-md-12">

                                    @livewire('admin.activity-component', ['account' => $account])


                                </div>


                                <div class="col-xxl-6 col-lg-12 col-md-12">
                                    <div class="row g-4">
                                        <div class="col-xxl-12">
                                            <div class="dashboard-contant-title">
                                                <h4>Outstanding Invoices <a href="/shop/account/invoices">View</a>
                                                </h4>
                                            </div>
                                            <div class="dashboard-detail">
                                                @include('shop.account.invoices')
                                            </div>
                                        </div>

                                        <div class="col-xxl-12 mt-3">
                                            <div class="dashboard-contant-title">
                                                <h4><span>Active Orders </span><a href="/shop/account/orders">View</a>
                                                </h4>
                                            </div>
                                            <div class="dashboard-detail">
                                                @include('shop.account.orders')
                                            </div>
                                        </div>


                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

