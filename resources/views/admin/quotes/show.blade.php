@extends('layouts.admin', [
    'title' => $quote->lead ? $quote->lead->company : $quote->account->name . " Quote #{$quote->id}",
    'crumbs' => $crumbs,
    'log' => $quote->logLink
])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">#{{$quote->id}} - {{$quote->name}} ({{$quote->status}})</h1>
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
            <div class="p-2 me-md-3">
                <div><span
                        class="h6 mb-0">{{$quote->term ? $quote->term ." months" : "MTM"}}</span>
                </div>
                <small class="text-muted text-uppercase">Term</small>
            </div>
            <div class="p-2 pe-lg-0">
                <div>
                    <span class="h6 mb-0">
                        @if($quote->lead && $quote->lead->agent)
                            {{$quote->lead->agent ? $quote->lead->agent->short : "No Agent"}}
                        @else
                            {{$quote->account->agent ? $quote->account->agent->short : "No Agent"}}
                        @endif
                    </span>
                </div>
                <small class="text-muted text-uppercase">Agent</small>
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
            @if($quote->status == 'Executed')
                <div class="alert border-warning">
                    <i class="fa fa-exclamation-circle"></i>
                    This quote was executed on {{$quote->activated_on->format("m/d/y")}} by {{$quote->contract_name}} and is no longer able to be edited.
                </div>
            @endif

            @if(!$quote->presentable)
                <div role="alert" class="alert border-warning mt-3">NOTE: This quote is not completed yet. If you are
                    finished with this quote and would like customers to be able to see it make sure you <a
                        href="/admin/quotes/{{$quote->id}}/presentable">
                        mark it as presentable.
                    </a>
                </div>
            @endif
            @include('admin.quotes.builder')
        </div>
    </div>
    @include('admin.quotes.decline_modal')

@endsection
