@extends('layouts.admin', ['title' => setting('brand.name') . " Dashboard", 'crumbs' => []])

@section('content')

    <div class="row row-deck mb-2">
        @if (!isInDevelopment() && (currentVersion()->version != latestVersion()->version))
            <div class="col-lg-12">
                <div class="card mb-3 text-center bg-primary-gradient">
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <div>
                            <h4 class="mt-4">Logic v{{latestVersion()->version}} Available</h4>
                            <p class="card-text fs-6 mb-5">{{latestVersion()->summary}}</p>

                            <a class="btn btn-lg bg-info text-uppercase px-4 lift confirm"
                               data-method="GET"
                               data-message="Are you sure you want to upgrade to v{{latestVersion()->version}}? Upon clicking proceed, the upgrade will be scheduled and will be done within the next 60 seconds."
                               href="/admin/upgrade"><i class="fa fa-angle-up"></i> Upgrade to
                                v{{latestVersion()->version}}
                            </a>

                            <a class="btn btn-lg bg-info text-uppercase px-4 lift" href="{{latestVersion()->changelog}}"
                               title="">
                                <i
                                    class="fa fa-book"></i> Review Changelog</a>
                        </div>
                    </div>
                </div>
            </div>

        @endif
        @if(count(\App\Models\Account::gettingStarted()) && !env('DEMO_MODE'))
            @include('admin.dashboard.help')
        @endif
        @include('admin.dashboard.widgets')


    </div>

    <div class="row">
        <div class="col-lg-8">

            <div class="row">
                <div class="col-lg-6">
                    @include('admin.dashboard.alerts')
                    @include('admin.dashboard.calendar')
                </div>
                <div class="col-lg-6">
                    {!! moduleHook('admin.dashboard.index') !!}
                    @include('admin.dashboard.graphs')
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            @livewire('admin.shop-monitor-component')
            @livewire('admin.dashboard-activity-component')
        </div>
    </div>

@endsection
