@extends('layouts.admin', ['title' => $lead->company . " Quote #{$quote->id}", 'crumbs' => [
     '/admin/leads' => "Leads",
     "/admin/leads/$lead->id" => $lead->company,
     "/admin/leads/$lead->id/quotes/" => "Quotes",
     "#$quote->id"
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">#{{$quote->id}} - {{$quote->name}}</h1>
            <small class="text-muted">{{$lead->company}} / {{$lead->contact}}</small>
        </div>
        <div class="col d-flex justify-content-lg-end mt-2 mt-md-0">
            <div class="p-2 me-md-3">
                <div><span class="h6 mb-0">${{number_format($quote->mrr,2)}}</span></div>
                <small class="text-muted text-uppercase">MRR</small>
            </div>
            <div class="p-2 me-md-3">
                <div><span class="h6 mb-0">${{number_format($quote->nrc,2)}}</span></div>
                <small class="text-muted text-uppercase">NRC</small>
            </div>
            <div class="p-2 pe-lg-0">
                <div><span class="h6 mb-0">{{$lead->agent->short}}</span></div>
                <small class="text-muted text-uppercase">Owner</small>
            </div>
        </div>
    </div> <!-- .row end -->

@endsection
@section('content')
    <div class="row">
        @include('admin.leads.profile.header')
    </div>
    <div class="row">
        <div class="col-lg-12">
            @if(sbus()->findSessionByQuote($quote))
                <div class="alert alert-info">
                    This quote is being actively viewed by your customer. You can live update the quote here and
                    it will be reflected instantly on the customer's side.
                </div>
            @endif
            @include('admin.quotes.builder')
        </div>
    </div>
    @include('admin.quotes.decline_modal')

@endsection
