@extends('layouts.admin', ['title' => $lead->company, 'crumbs' => [
     '/admin/leads' => "Leads",
     $lead->company .  " ($lead->contact)"
]])

@section('content')
<div class="row">
    <div class="col-lg-6">
        @livewire('admin.partner-lead-component', ['lead' => $lead])

    </div>

    <div class="col-lg-6">
        @livewire('admin.activity-component', ['lead' => $lead])
    </div>
</div>
@endsection
