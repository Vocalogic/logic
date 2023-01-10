@extends('layouts.shop.main', ['title' => "Invoice #{$invoice->id}", 'crumbs' => [
     "/shop" => "Home",
     "/shop/account" => "My Account",
     "/shop/account/invoices" => "Invoices",
     "Invoice #$invoice->id"
]])

@section('content')

    <section class="order-detail">
        <div class="container-fluid-lg">
            <div class="row g-sm-4 g-3">
                <div class="col-xxl-3 mb-3">
                <a href="/shop/account" class="btn bg-theme"><i class="fa fa-arrow-left"></i> &nbsp; Back </a>
                </div>

                <div class="col-xxl-12 col-xl-12 col-lg-12">
                    <div class="row g-sm-4 g-3">
                        <div class="col-xl-3 col-sm-6">
                            <div class="order-details-contain">
                                <div class="order-tracking-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="feather feather-package text-content">
                                        <line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line>
                                        <path
                                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                    </svg>
                                </div>

                                <div class="order-details-name">
                                    <h5 class="text-content">Invoice Created</h5>
                                    <h2 class="theme-color">{{$invoice->created_at->format("m/d/y h:ia")}}</h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-sm-6">
                            <div class="order-details-contain">
                                <div class="order-tracking-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="feather feather-truck text-content">
                                        <rect x="1" y="3" width="15" height="13"></rect>
                                        <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                        <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                        <circle cx="18.5" cy="18.5" r="2.5"></circle>
                                    </svg>
                                </div>

                                <div class="order-details-name">
                                    <h5 class="text-content">Invoice Total</h5>
                                    <h2 class="theme-color">${{moneyFormat($invoice->total)}}</h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-sm-6">
                            <div class="order-details-contain">
                                <div class="order-tracking-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="feather feather-crosshair text-content">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="22" y1="12" x2="18" y2="12"></line>
                                        <line x1="6" y1="12" x2="2" y2="12"></line>
                                        <line x1="12" y1="6" x2="12" y2="2"></line>
                                        <line x1="12" y1="22" x2="12" y2="18"></line>
                                    </svg>
                                </div>

                                <div class="order-details-name">
                                    <h5 class="text-content">Invoice Balance</h5>
                                    <h2 class="theme-color">${{moneyFormat($invoice->balance)}}</h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-sm-6">
                            <div class="order-details-contain">
                                <div class="order-tracking-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="feather feather-crosshair text-content">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="22" y1="12" x2="18" y2="12"></line>
                                        <line x1="6" y1="12" x2="2" y2="12"></line>
                                        <line x1="12" y1="6" x2="12" y2="2"></line>
                                        <line x1="12" y1="22" x2="12" y2="18"></line>
                                    </svg>
                                </div>

                                <div class="order-details-name">
                                    <h5 class="text-content">Invoice Due</h5>
                                    <h2 class="theme-color">{{$invoice->due_on->format("M d, Y")}}</h2>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <div class="row g-sm-4 g-3 mt-3">
                <div class="col-lg-8">
                    @include('shop.account.invoices.details')
                </div>

                <div class="col-lg-4">

                    <div class="summery-box p-sticky">

                        <div class="button-group cart-button">
                            <ul>
                                @if($invoice->balance > 0)
                                <li>
                                    <button onclick="location.href = '/shop/account/invoices/{{$invoice->id}}/pay';" class="btn btn-animation proceed-btn fw-bold">Make Payment</button>
                                </li>
                                @endif

                                <li>
                                    <button onclick="location.href = '/shop/account/invoices/{{$invoice->id}}/download';" class="btn btn-light shopping-button text-dark">
                                        <i class="fa-solid fa-download"></i>Download Invoice</button>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </section>

@endsection
