@extends('layouts.shop.main', ['title' => "My Invoices", 'crumbs' => [
     "/shop" => "Home",
     "/shop/account" => "My Account",
     "Invoices",

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
                                <h2>View Invoices</h2>
                                <span class="title-leaf">
                                            <svg class="icon-width bg-gray">
                                                <use xlink:href="/ec/assets/svg/leaf.svg#leaf"></use>
                                            </svg>
                                        </span>
                            </div>
                        </div>



                        <section class="faq-box-contain">
                            <div class="faq-accordion">
                                <div class="accordion" id="invoiceAccordion">

                                    @foreach(\App\Models\Invoice::invoicesByYear(user()->account) as $year => $invoices)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="year-{{$year}}">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#y-{{$year}}" aria-expanded="true" aria-controls="y-{{$year}}">
                                                {{$year}} <i class="fa-solid fa-angle-down"></i>
                                            </button>
                                        </h2>
                                        <div id="y-{{$year}}" class="accordion-collapse collapse show" aria-labelledby="year-{{$year}}" data-bs-parent="#invoiceAccordion">
                                            <div class="accordion-body">
                                                    @include('shop.account.invoices.single', ['invoices' => $invoices])
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach


                                </div>
                            </div>

                        </section>

                    </div>
                </div>




            </div>
        </div>
    </section>
@endsection
