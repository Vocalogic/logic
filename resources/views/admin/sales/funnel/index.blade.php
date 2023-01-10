@extends('layouts.admin', ['title' => "Sales Funnel", 'crumbs' => [
     "Sales Funnel"
]])


@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Sales Funnel</h1>
            <small class="text-muted">Get an update to date snapshot of your current leads</small>
        </div>

    </div> <!-- .row end -->

@endsection

@section('content')
    <div class="row g-3 row-deck mb-4">
        <div class="col-xxl-5 col-xl-12 col-lg-12">
            <div class="card bg-transparent border-0">
                @include('admin.sales.funnel.stat_grid')
            </div>
        </div>

        <div class="col-xxl-7 col-xl-12 col-lg-12">
                <div class="card">
                    <div class="lchart" id="totalLeads"
                         data-title="Active Leads vs Activity"
                         data-height="400"
                         data-url="/admin/graph/LEADS_TOTAL?days=14&with=LEAD_ACTIVITY&s0=line&s1=bar"
                         data-xtype="datetime"
                         data-type="line"
                         data-y="Total Leads"
                         data-disable-toolbar="true"
                         data-wait="Getting Lead Totals...">

                    </div>
                </div>
        </div>
    </div>

    @include('admin.sales.funnel.goals')
@endsection


@section('javascript')
    <script>

        var options = {
            series: {!! \App\Operations\Admin\GoalStats::getMonthly() !!},
            chart: {
                height: {{75 * \App\Models\User::where('account_id', 1)->count()}},
                type: 'bar'
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                }
            },
            colors: ['#00E396'],
            dataLabels: {
                formatter: function(val, opt) {
                    const goals =
                        opt.w.config.series[opt.seriesIndex].data[opt.dataPointIndex]
                            .goals

                    if (goals && goals.length) {
                        return `${val} / ${goals[0].value}`
                    }
                    return val
                }
            },
            legend: {
                show: true,
                showForSingleSeries: true,
                customLegendItems: ['Actual', 'Expected'],
                markers: {
                    fillColors: ['#00E396', '#775DD0']
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#monthlyChart"), options);
        chart.render();



        var options = {
            series: {!! \App\Operations\Admin\GoalStats::getQuarterly() !!},
            chart: {
                height: {{75 * \App\Models\User::where('account_id', 1)->count()}},                type: 'bar'
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                }
            },
            colors: ['#00E396'],
            dataLabels: {
                formatter: function(val, opt) {
                    const goals =
                        opt.w.config.series[opt.seriesIndex].data[opt.dataPointIndex]
                            .goals

                    if (goals && goals.length) {
                        return `${val} / ${goals[0].value}`
                    }
                    return val
                }
            },
            legend: {
                show: true,
                showForSingleSeries: true,
                customLegendItems: ['Actual', 'Expected'],
                markers: {
                    fillColors: ['#00E396', '#775DD0']
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#quarterlyChart"), options);
        chart.render();


    </script>

@endsection

