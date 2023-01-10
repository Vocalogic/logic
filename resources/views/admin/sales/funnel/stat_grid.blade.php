<div class="row g-3">
    <div class="col-xxl-6 col-xl-3 col-lg-6 col-md-6">
        <div class="card">
            <div class="card-body">
                <span class="text-uppercase">Total Leads</span>
                <h4 class="mb-0 mt-2">{{\App\Models\Lead::where('active', true)->count()}}</h4>
                <small class="text-muted">Totals for this week</small>
            </div>
            <div id="sess" class="lchart"
                 data-type="bar"
                 data-spark="true"
                 data-url="/admin/graph/LEADS_TOTAL?days=14&seriesType=sparkSeries&color=2">
            </div>
        </div>
    </div>
    <div class="col-xxl-6 col-xl-3 col-lg-6 col-md-6">
        <div class="card">
            <div class="card-body">
                <span class="text-uppercase">Total Activity</span>
                <h4 class="mb-0 mt-2">{{\App\Models\Activity::where('type', 'LEAD')->whereDate('created_at', '>', now()->subWeek())->count()}}</h4>
                <small class="text-muted">Updates made this week</small>
            </div>
            <div id="sparkact" class="lchart"
                 data-type="line"
                 data-spark="true"
                 data-url="/admin/graph/LEAD_ACTIVITY?days=14&seriesType=sparkSeries&color=1">
            </div>
        </div>
    </div>
    <div class="col-xxl-6 col-xl-3 col-lg-6 col-md-6">
        <div class="card">
            <div class="card-body">
                <span class="text-uppercase">Forecasted MRR</span>
                <h4 class="mb-0 mt-2">
                    ${{moneyFormat(_metrics(now()->subDays(6), now(), null, \App\Enums\Core\MetricType::TotalForecasted, true)->sum('value') ? :0)}}</h4>
                <small class="text-muted">Forecasted MRR this week</small>
            </div>
            <div id="sparkfore" class="lchart"
                 data-type="line"
                 data-spark="true"
                 data-url="/admin/graph/QUOTE_FORECAST?days=14&seriesType=sparkSeries&color=3&diff=true&tally=sum">
            </div>
        </div>
    </div>
    <div class="col-xxl-6 col-xl-3 col-lg-6 col-md-6">
        <div class="card">
            <div class="card-body">
                <span class="text-uppercase">Lost Leads</span>
                <h4 class="mb-0 mt-2">
                    {{_metrics(now()->subDays(6), now(), null, \App\Enums\Core\MetricType::TotalLost, true)->sum('value') ? :0}}
                </h4>
                <small class="text-muted">Number of leads lost</small>
            </div>
            <div id="sparklost" class="lchart"
                 data-type="line"
                 data-spark="true"
                 data-url="/admin/graph/LEAD_LOST?days=14&seriesType=sparkSeries&color=3&diff=true&tally=sum">
            </div>
        </div>
    </div>
</div>
