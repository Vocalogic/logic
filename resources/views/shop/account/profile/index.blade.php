@extends('layouts.shop.main', ['title' => "My Profile", 'crumbs' => [
     "/shop" => "Home",
     "/shop/account" => "My Account",
     "My Profile"
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

                        <div class="dashboard-profile">
                            <div class="title">
                                <h2>{{$account->name}} Profile</h2>
                                <span class="title-leaf">
                                            <svg class="icon-width bg-gray">
                                                <use xlink:href="/ec/assets/svg/leaf.svg#leaf"></use>
                                            </svg>
                                        </span>
                            </div>

                            <div class="profile-detail dashboard-bg-box">
                                <div class="dashboard-title">
                                    <h3>Profile Details</h3>
                                </div>
                                <div class="profile-name-detail">
                                    <div class="d-sm-flex align-items-center d-block">
                                        <h3>{{user()->name}}</h3>

                                    </div>

                                </div>

                                <div class="location-profile">
                                    <ul>
                                        <li>
                                            <div class="location-box">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor" stroke-width="2"
                                                     stroke-linecap="round"
                                                     stroke-linejoin="round" class="feather feather-map-pin">
                                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                                    <circle cx="12" cy="10" r="3"></circle>
                                                </svg>
                                                <h6>{{$account->address}}, {{$account->city}}</h6>
                                            </div>
                                        </li>

                                        <li>
                                            <div class="location-box">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor" stroke-width="2"
                                                     stroke-linecap="round"
                                                     stroke-linejoin="round" class="feather feather-mail">
                                                    <path
                                                        d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                                    <polyline points="22,6 12,13 2,6"></polyline>
                                                </svg>
                                                <h6>{{user()->email}}</h6>
                                            </div>
                                        </li>

                                        <li>
                                            <div class="location-box">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor" stroke-width="2"
                                                     stroke-linecap="round"
                                                     stroke-linejoin="round" class="feather feather-check-square">
                                                    <polyline points="9 11 12 14 22 4"></polyline>
                                                    <path
                                                        d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                                </svg>
                                                <h6>Customer since {{user()->account->created_at->format("F d, Y")}}</h6>
                                            </div>
                                        </li>
                                    </ul>
                                </div>


                            </div>

                            <div class="profile-about dashboard-bg-box">
                                <div class="row">
                                    <div class="col-xxl-7">
                                        <div class="dashboard-title mb-3">
                                            <h3>Billing Info</h3>
                                        </div>
                                    <div class="card">
                                        <div class="card-body">

                                        @if($account->merchant_payment_token)
                                            @if($account->merchant_payment_type)
                                                <span class="text-success"><i class="fa fa-check"></i>You have a {{$account->merchant_payment_type}} ending in {{$account->merchant_payment_last4}} on file with {{setting('brand.name')}}</span>
                                            @else
                                                <span class="text-success"><i class="fa fa-check"></i>&nbsp;&nbsp; <b>You have a credit card on file with {{setting('brand.name')}}</b></span>
                                            @endif
                                        @else
                                            <span class="text-danger"><i class="fa fa-exclamation-circle"></i> You have no credit card on file.</span>
                                        @endif
                                        @if(getIntegration(\App\Enums\Core\IntegrationType::Merchant) == \App\Enums\Core\IntegrationRegistry::Stripe)
                                            @include('admin.accounts.billing.merchant_stripe')
                                        @endif


                                        @if(getIntegration(\App\Enums\Core\IntegrationType::Merchant) == \App\Enums\Core\IntegrationRegistry::LogicPay)
                                            <div class="card">
                                                @include('admin.accounts.billing.merchant_logic')
                                            </div>
                                        @endif

                                        </div>
                                    </div>

                                            <table class="table">
                                                <tbody>
                                                <tr>
                                                    <td>Payment Method:</td>
                                                    <td>{{$account->payment_method->getDescription()}}
                                                        @if(!$account->payment_method->canSelfUpdate())
                                                             (Contact Support to Update)
                                                        @endif</td>
                                                </tr>
                                                <tr>
                                                    <td>Payment Details:</td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-lg-12"></div>
                                                        </div>


                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Monthly Services:</td>
                                                    <td>${{moneyFormat($account->mrr)}}/mo</td>
                                                </tr>
                                                <tr>
                                                    <td>Next Invoice Cycle :</td>
                                                    <td>
                                                        @if($account->next_bill)
                                                            {{$account->next_bill->format("M d, Y")}}
                                                            @else
                                                                Never
                                                        @endif
                                                    </td>
                                                </tr>

                                                </tbody>
                                            </table>


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

@section('javascript')
    @if(getIntegration(\App\Enums\Core\IntegrationType::Merchant) == \App\Enums\Core\IntegrationRegistry::LogicPay)
        <script type="text/javascript" src="/assets/js/logicpay.js"></script>
    @endif
@endsection
