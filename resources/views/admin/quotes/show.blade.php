@extends('layouts.admin', ['title' => $quote->lead ? $quote->lead->company : $quote->account->name . " Quote #{$quote->id}", 'crumbs' => $crumbs])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">#{{$quote->id}} - {{$quote->name}}</h1>
            <small class="text-muted">{{$quote->lead ? $quote->lead->company : $quote->account->name}} /
                {{$quote->lead ? $quote->lead->contact : $quote->account->admin->name}}</small>
        </div>
        <div class="col d-flex justify-content-lg-end mt-2 mt-md-0">
            <div class="p-2 me-md-3">
                <div><span class="h6 mb-0">${{moneyFormat($quote->mrr)}}</span></div>
                <small class="text-muted text-uppercase">MRR</small>
            </div>
            <div class="p-2 me-md-3">
                <div><span class="h6 mb-0">${{moneyFormat($quote->nrc)}}</span></div>
                <small class="text-muted text-uppercase">NRC</small>
            </div>
            <div class="p-2 pe-lg-0">
                <div><span class="h6 mb-0">{{$quote->lead ? $quote->lead->agent->short : $quote->account->agent->short}}</span></div>
                <small class="text-muted text-uppercase">Owner</small>
            </div>
        </div>
    </div> <!-- .row end -->

@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            @if(sbus()->findSessionByQuote($quote))
                <div class="alert alert-info">
                    This quote is being actively viewed by your customer. You can live update the quote here and
                    it will be reflected instantly on the customer's side.
                </div>
            @endif
            @if(!$quote->approved && $quote->lead && $quote->lead->agent && $quote->lead->agent->requires_approval)
                <div class="alert alert-dark">
                    <i class="fa fa-exclamation-circle"></i> This quote has not been approved.
                    @if(!user()->requires_approval)
                        <a class="confirm"
                           data-message="Are you sure you want to approve this quote?"
                           data-method="GET"
                           href="/admin/quotes/{{$quote->id}}/approve">Approve quote for sending.</a>
                    @endif
                </div>
            @endif
            @include('admin.quotes.builder')
        </div>
    </div>
    @include('admin.quotes.decline_modal')

@endsection
