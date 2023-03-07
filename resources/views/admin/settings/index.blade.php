@extends('layouts.admin', ['title' => "Logic Settings",
'video' => "https://www.youtube.com/watch?v=pYMHh8H4J_k&t=2s",
'docs' => "https://logic.readme.io/docs/brand-settings",
'crumbs' => [
    'Settings'
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Logic Settings</h1>
            <small class="text-muted">Update your brand, integrations and more.</small>
        </div>

    </div> <!-- .row end -->
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-2">
            @include('admin.settings.setting_menu')
        </div>
        <div class="col-lg-10">
            @switch($tab)
                @case('brand')

                    @include('admin.settings.render', ['title' => "Brand Settings", 'sub' => "Update your company information and more.", 'tab' => 'brand'])

                    <div class="mt-3">
                        @include('admin.settings.render', ['title' => "Brand Images", 'sub' => "Upload your brand logos", 'tab' => 'brandImage'])
                    </div>

                    @break

                @case('lead')
                    <div class="col-lg-8">
                        @include('admin.settings.render', ['title' => "Lead Settings", 'sub' => "Update how your lead system works"])
                    </div>

                    @break

                @case('quote')
                    <div class="col-lg-12">
                        @include('admin.settings.render', ['title' => "Quote Settings", 'sub' => "Set up quote settings"])
                    </div>
                    <div class="col-lg-12 mt-3">
                        @include('admin.settings.render', ['title' => "Master Services Agreement", 'sub' => "Set up MSA", 'tab' => 'MSA'])
                    </div>

                    @break


                @case('invoice')
                    <div class="col-lg-12">
                        @include('admin.settings.render', ['title' => "Invoice Settings", 'sub' => "Set up invoicing settings"])
                    </div>
                    @break

                @case('mail')
                    @if(env('DEMO_MODE') == true)
                        <div class="col-lg-12">
                            <div class="alert alert-primary">Mailer configurations are disabled in demo mode.</div>
                        </div>
                    @else
                        <div class="col-lg-12">
                            @include('admin.settings.render', ['title' => "Mail Settings", 'sub' => "Configure how mail is sent from logic."])
                        </div>
                    @endif
                    @break

                @case('account')
                    <div class="col-lg-12">
                        @include('admin.settings.render', ['title' => "Account Settings", 'sub' => "Account Related Settings"])
                    </div>
                    @break

                @case('shop')
                    <div class="col-lg-12">
                        @include('admin.settings.render', ['title' => "Shop Settings", 'sub' => "Settings for your Customer Portal"])
                    </div>
                    @break

                @case('order')
                    <div class="col-lg-12">
                        @include('admin.settings.render', ['title' => "Order Fulfillment Settings", 'sub' => "Settings for Order Fulfillment"])
                    </div>
                    @break

                @case('project')
                    <div class="col-lg-12">
                        @include('admin.settings.render', ['title' => "Project Operation Settings", 'sub' => "Set preferences for projects"])
                    </div>
                    @break

            @endswitch
        </div>
    </div>

@endsection
