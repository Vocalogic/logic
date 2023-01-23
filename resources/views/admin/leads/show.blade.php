@extends('layouts.admin', ['title' => $lead->company, 'crumbs' => [
     '/admin/leads' => "Leads",
     $lead->company .  " ($lead->contact)"
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">{{$lead->company}}
                ({{$lead->type ? $lead->type->name : "Unknown Type"}})</h1>
            <small class="text-muted">Manage the lead profile for {{$lead->contact}}.</small>
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
    <div class="row mb-3">
        <div class="col-lg-8">
            @include('admin.leads.profile.header')


        @switch($tab)
            @case('overview')
            @include('admin.leads.profile.overview.index')
            @break
            @case('events')
            @include('admin.leads.events.index')
            @break
        @endswitch
        </div>

        <div class="col-lg-4">
            @livewire('admin.activity-component', ['lead' => $lead])
        </div>
    </div>
@endsection
