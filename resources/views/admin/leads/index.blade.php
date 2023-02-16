@extends('layouts.admin', ['title' => "Leads", 'crumbs' => [
     "Leads",
]])

@props([
    'origins' => array_replace([0 => '-- Select Origin --'], \App\Models\LeadOrigin::all()->pluck('name', 'id')->all()),
     'lead_types' => \App\Models\LeadType::all()->pluck("name", 'id')->all(),
     'statuses' => \App\Models\LeadStatus::getSelectable()
     ])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Active Leads</h1>
            <small class="text-muted">Manage Leads and Forecast</small>
        </div>
    </div> <!-- .row end -->

@endsection

@section('content')
    <div class="row">

        <div class="col-lg-2 mt-2">
            @include('admin.leads.statusbar')
        </div>
        <div class="col-lg-10 mt-2">
           @livewire('iterators.lead-iterator-component')
        </div>
    </div>



    <x-modal name="newLead" title="New Lead" size="xl">
        <form method="post" action="/admin/leads">
            @method('POST')
            @csrf
            <div class="row no-gutters">
                <div class="col-lg-5 text-center">
                    <div class="mt-3">
                        <h6>Creating a new Lead</h6>
                        <p class="card-text">
                            Enter the minimum required information about the new lead here. Once saved, you will
                            be presented with a form where you can add additional details.
                        </p>
                    </div>
                    <div class="mt-5">
                        <i class="fa fa-user fa-4x"></i>
                    </div>
                </div>

                <div class="col-lg-7">
                    <h6 class="fw-bold">Basic Information</h6>

                    <div class="col-12 mb-3">
                        <x-form-input name="company" label="Company Name" icon="building" float="true">
                            Enter the company name
                        </x-form-input>
                    </div>


                    <div class="col-12 mb-3">
                        <x-form-input name="contact" label="Contact Name" icon="people" float="true">
                            Enter contact's full name
                        </x-form-input>

                    </div>

                    <div class="row">

                        <div class="col-6 mb-3">
                            <x-form-input name="email" label="E-mail Address" float="true">
                                Email address for contact
                            </x-form-input>
                        </div>


                        <div class="col-6 mb-3">
                            <x-form-select name="lead_type_id" label="Select Lead Type" :options="$lead_types"
                                           float="true">
                                Select the lead type
                            </x-form-select>
                        </div>
                    </div>


                    <div class="col-12 mb-3">
                        <button type="submit" class="btn btn-primary ladda pull-right" data-style="zoom-out">
                            <i class="fa fa-save"></i> Create Lead
                        </button>
                    </div>
                </div>

            </div>
        </form>

    </x-modal>

@endsection
