@extends('layouts.shop.main', ['title' => "View Quote"])

@section('content')
    <section class="user-dashboard-section section-b-space">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-xxl-3 col-lg-4">
                    @if($quote->presentable)
                        <div class="alert alert-success">This quote is presentable and can be viewable by the
                            customer.
                        </div>
                    @else
                        <div class="alert alert-warning">This quote is currently in a draft state and is not viewable by
                            the customer. <a href="/sales/leads/{{$lead->id}}/quotes/{{$quote->id}}/presentable">Make
                                presentable</a></div>
                    @endif
                    @include('shop.sales.menu', ['quote' => $quote])
                </div>
                <div class="col-xxl-9 col-lg-8">
                    @include('shop.sales.leads.quotes.list')

                </div>
            </div>
        </div>
    </section>

@endsection
