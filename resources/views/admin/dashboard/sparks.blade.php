<div class="row mb-2">
    <div class="col-xxl-6 col-xl-3 col-lg-6 col-md-6">
        <div class="card">
            <div class="card-body">
                <span class="text-uppercase">Total MRR</span>
                <h4 class="mb-0 mt-2">${{moneyFormat(\App\Models\Account::getTotalMRR())}}</h4>
                <small class="text-muted">Increase over last week</small>
            </div>
            <div id="totalmrr" class="lchart"
                 data-type="bar"
                 data-spark="true"
                 data-url="/admin/graph/MRR?days=6&seriesType=sparkSeries&color=2&diff=true">
            </div>
        </div>
    </div>

    <div class="col-xxl-6 col-xl-3 col-lg-6 col-md-6">
        <div class="card">
            <div class="card-body">
                <span class="text-uppercase">Total Invoiced</span>
                <h4 class="mb-0 mt-2">${{moneyFormat(\App\Models\Invoice::getAllTotals())}}</h4>
                <small class="text-muted">Invoiced over last week</small>
            </div>
            <div id="totalinv" class="lchart"
                 data-type="bar"
                 data-spark="true"
                 data-url="/admin/graph/INVOICED?days=6&seriesType=sparkSeries&color=0&diff=true">
            </div>
        </div>
    </div>


</div>

<div class="row">
    <div class="col-xxl-6 col-xl-3 col-lg-6 col-md-6">
        <div class="card">
            <div class="card-body">
                <span class="text-uppercase">Total Leads</span>
                <h4 class="mb-0 mt-2">{{\App\Models\Lead::where('active', true)->count()}}</h4>
                <small class="text-muted">Totals for last week</small>
            </div>
            <div id="sess" class="lchart"
                 data-type="bar"
                 data-spark="true"
                 data-url="/admin/graph/LEADS_TOTAL?days=6&seriesType=sparkSeries&color=2">
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
                 data-url="/admin/graph/QUOTE_FORECAST?days=6&seriesType=sparkSeries&color=3&diff=true&tally=sum">
            </div>
        </div>
    </div>
</div>

