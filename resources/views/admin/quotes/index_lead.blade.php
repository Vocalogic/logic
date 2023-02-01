@extends('layouts.admin', ['title' => $lead->company . " Quotes", 'crumbs' => [
     '/admin/leads' => "Leads",
     "/admin/leads/$lead->id" => $lead->company,
     "Quotes"
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Quotes for {{$lead->company}}
                ({{$lead->type ? $lead->type->name : "Unknown Type"}})</h1>
            <small class="text-muted">Manage the quotes for {{$lead->contact}}.</small>
        </div>
        <div class="col d-flex justify-content-lg-end mt-2 mt-md-0">
            <div class="p-2 me-md-3">
                <div><span class="h6 mb-0">${{moneyFormat($lead->primaryMrr)}}</span></div>
                <small class="text-muted text-uppercase">MRR</small>
            </div>
            <div class="p-2 me-md-3">
                <div><span class="h6 mb-0">${{moneyFormat($lead->primaryNrc)}}</span></div>
                <small class="text-muted text-uppercase">NRC</small>
            </div>
            <div class="p-2 pe-lg-0">
                <div><span class="h6 mb-0">{{$lead->agent?->short}}</span></div>
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
            @if(!$lead->quotes->count())
                <div class="card mt-3">
                    <div class="card-body text-center">
                        <img src="/assets/images/no-data.svg" class="w120" alt="No Data">
                        <div class="mt-4 mb-3">
                            <span class="text-muted">No quotes found.</span>
                        </div>
                        <a class="btn btn-primary border lift live"
                           href="/admin/quotes/create?lead_id={{$lead->id}}"><i class="fa fa-plus"></i>
                            Create New Quote</a>
                    </div>
                </div>
            @else

                <a class="btn btn-primary mt-3 mb-2 live" data-title="Create Quote for {{$lead->company}}" href="/admin/quotes/create?lead_id={{$lead->id}}"><i
                        class="fa fa-plus"></i> New Quote</a>

                @include('admin.quotes.list', ['quotes' => $lead->quotes()->where('archived', false)->get()])
            @endif
        </div>
    </div>

@endsection
