@extends('layouts.shop.main', ['title' => "Pay Invoice #{$invoice->id}", 'crumbs' => [
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
                    <a href="/shop/account/invoices/{{$invoice->id}}" class="btn text-white bg-success"><i
                            class="fa fa-arrow-left"></i> &nbsp; Back </a>
                </div>

                <div class="row g-sm-4 g-3 mt-3">
                    <div class="col-lg-8">
                        @include('shop.account.invoices.details')
                    </div>

                    <div class="col-lg-4">
                        @if(!user()->account->merchant_payment_token)
                            <div class="alert alert-info">You do not currently have a card on file
                                with {{setting('brand.name')}}. To pay your invoice you must
                                add a card below. We will attempt to <b>pre-authorize $1.00</b> to validate the card.
                            </div>
                            <h4 class="card-title mt-3">Add Payment Card</h4>
                            <p>
                                Use the form below to update your credit card information. This card will be used for
                                processing
                                your monthly services and any invoices you have outstanding. If you wish to update your
                                credit card
                                you may do so by visiting your <a href="/shop/account/profile">Account Profile Page.</a>
                            </p>

                            @if(getIntegration(\App\Enums\Core\IntegrationType::Merchant) == \App\Enums\Core\IntegrationRegistry::Stripe)
                                @include('admin.accounts.profile.merchant_stripe')
                            @endif
                        @endif


                        @if(user()->account->merchant_payment_token)
                            <div class="alert alert-info">You have a {{user()->account->merchant_payment_type}}
                                ending in x{{user()->account->merchant_payment_last4}} on file
                                with {{setting('brand.name')}}.
                            </div>
                            @if($invoice->balance > 0)
                                @livewire('shop.pay-invoice-component', ['invoice' => $invoice])
                            @else
                                <div class="alert alert-success">This invoice has been paid. Return to <a href="/shop/account">My Account</a>.</div>
                            @endif

                        @endif


                    </div>

                </div>


            </div>
        </div>
    </section>

@endsection
