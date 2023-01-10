<div class="row row-cols-lg-4 row-cols-md-2 row-cols-sm-2 row-cols-1 mb-4 row-deck">
    <div class="col">
        <div class="card p-3">
            @php
                $invoice = \App\Operations\Admin\WidgetGenerator::get('invoicedToday');
            @endphp
            <div class="d-flex align-items-center">
                <div class="avatar rounded-circle no-thumbnail bg-light"><img class="avatar"
                                                                              src="/icons/{{$invoice->icon}}.png"></div>
                <div class="flex-fill ms-3 text-truncate">
                    <div class="small text-uppercase">{{$invoice->name}}</div>
                    <div><span class="h6 mb-0 fw-bold">${{moneyFormat($invoice->total)}}</span> <small
                            class="text-{{$invoice->color}}">{{$invoice->perc}}%</small></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card p-3">
            @php
                $invoice = \App\Operations\Admin\WidgetGenerator::get('outstandingInvoices');
            @endphp
            <div class="d-flex align-items-center">
                <div class="avatar rounded-circle no-thumbnail bg-light"><img class="avatar"
                                                                              src="/icons/{{$invoice->icon}}.png"></div>
                <div class="flex-fill ms-3 text-truncate">
                    <div class="small text-uppercase">{{$invoice->name}}</div>
                    <div><span class="h6 mb-0 fw-bold">${{moneyFormat($invoice->total)}}</span></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card p-3">
            @php
                $invoice = \App\Operations\Admin\WidgetGenerator::get('getTransactions');
            @endphp
            <div class="d-flex align-items-center">
                <div class="avatar rounded-circle no-thumbnail bg-light"><img class="avatar"
                                                                              src="/icons/{{$invoice->icon}}.png"></div>
                <div class="flex-fill ms-3 text-truncate">
                    <div class="small text-uppercase">{{$invoice->name}}</div>
                    <div><span class="h6 mb-0 fw-bold">${{moneyFormat($invoice->total)}}</span> <small
                            class="text-{{$invoice->color}}">{{$invoice->perc}}%</small></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card p-3">
            @php
                $invoice = \App\Operations\Admin\WidgetGenerator::get('getMRR');
            @endphp
            <div class="d-flex align-items-center">
                <div class="avatar rounded-circle no-thumbnail bg-light"><img class="avatar"
                                                                              src="/icons/{{$invoice->icon}}.png"></div>
                <div class="flex-fill ms-3 text-truncate">
                    <div class="small text-uppercase">{{$invoice->name}}</div>
                    <div><span class="h6 mb-0 fw-bold">${{moneyFormat($invoice->total)}}</span></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row row-cols-lg-4 row-cols-md-2 row-cols-sm-2 row-cols-1 mb-4 row-deck">

    <div class="col">
        <div class="card">
            @php
                $invoice = \App\Operations\Admin\WidgetGenerator::get('getLeads');
            @endphp

            <div class="card-body d-flex align-items-center">
                <div class=""><img class="avatar" src="/icons/{{$invoice->icon}}.png"/></div>
                <div class="flex-fill ms-3 text-truncate">
                    <span class="text-muted small text-uppercase">{{$invoice->name}}</span>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fs-6 fw-bold">{{$invoice->total}}</span>
                        <span class="small text-{{$invoice->color}}">{{$invoice->perc}}% <i class="fa fa-level-{{$invoice->direction}}"></i></span>
                    </div>
                </div>
            </div>
            <div id="sess" class="lchart"
                 data-type="bar"
                 data-spark="true"
                 data-url="/admin/graph/LEADS_TOTAL?days=30&seriesType=sparkSeries&color=2">
            </div>
        </div>
    </div>


    <div class="col">
        <div class="card">
            @php
                $invoice = \App\Operations\Admin\WidgetGenerator::get('quotedAmount');
            @endphp

            <div class="card-body d-flex align-items-center">
                <div class=""><img class="avatar" src="/icons/{{$invoice->icon}}.png"/></div>
                <div class="flex-fill ms-3 text-truncate">
                    <span class="text-muted small text-uppercase">{{$invoice->name}}</span>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fs-6 fw-bold">${{moneyFormat($invoice->total)}}</span>
                        <span class="small text-{{$invoice->color}}">{{$invoice->perc}}% <i class="fa fa-level-{{$invoice->direction}}"></i></span>
                    </div>
                </div>
            </div>
            <div id="quotedValue" class="lchart"
                 data-type="bar"
                 data-spark="true"
                 data-url="/admin/graph/QUOTE_VALUE?days=30&seriesType=sparkSeries&color=1">
            </div>
        </div>
    </div>


    <div class="col">
        <div class="card">
            @php
                $invoice = \App\Operations\Admin\WidgetGenerator::get('getForecasted');
            @endphp

            <div class="card-body d-flex align-items-center">
                <div class=""><img class="avatar" src="/icons/{{$invoice->icon}}.png"/></div>
                <div class="flex-fill ms-3 text-truncate">
                    <span class="text-muted small text-uppercase">{{$invoice->name}}</span>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fs-6 fw-bold">${{moneyFormat($invoice->total)}}</span>
                        <span class="small text-{{$invoice->color}}">{{$invoice->perc}}% <i class="fa fa-level-{{$invoice->direction}}"></i></span>
                    </div>
                </div>
            </div>
            <div id="foreValue" class="lchart"
                 data-type="bar"
                 data-spark="true"
                 data-url="/admin/graph/QUOTE_MONTH_FORECAST?days=30&seriesType=sparkSeries&color=1">
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            @php
                $invoice = \App\Operations\Admin\WidgetGenerator::get('getEcommerceQuotes');
            @endphp

            <div class="card-body d-flex align-items-center">
                <div class=""><img class="avatar" src="/icons/{{$invoice->icon}}.png"/></div>
                <div class="flex-fill ms-3 text-truncate">
                    <span class="text-muted small text-uppercase">{{$invoice->name}}</span>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fs-6 fw-bold">${{moneyFormat($invoice->total)}}</span>
                        <span class="small text-{{$invoice->color}}">{{$invoice->perc}}% <i class="fa fa-level-{{$invoice->direction}}"></i></span>
                    </div>
                </div>
            </div>
            <div id="ecValue" class="lchart"
                 data-type="bar"
                 data-spark="true"
                 data-url="/admin/graph/QUOTE_MONTH_ECOMMERCE?days=30&seriesType=sparkSeries&color=1">
            </div>
        </div>
    </div>


</div>
