<div class="row">
    <div class="col-lg-8">

        <div class="row">
            <div class="col-lg-12">
                <div class="lchart" id="monthlyMrr"
                     data-title="Monthly MRR (last 4 months)"
                     data-height="300"
                     data-url="/admin/graph/MRR_ACCOUNT?fn=getInvoicedMRRDiff&account={{$account->id}}&seriesType=timeSeries"
                     data-xtype="datetime"
                     data-type="line"
                     data-y="Total MRR"
                     data-disable-toolbar="true"
                     data-wait="Getting Historical MRR...">
                </div>
            </div>


        </div>
        <div class="row">
            @include('admin.accounts.overview.alerts')
        </div>

    </div>


    <div class="col-lg-4">
        @if($account->account_balance)
            <div class="card mb-3 p-3 border-dark">
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle no-thumbnail bg-light"><i class="fa fa-dollar fa-lg"></i></div>
                    <div class="flex-fill ms-3 text-truncate">
                        <div class="small">Outstanding Balance</div>
                        <span class="h5 mb-0">${{moneyFormat($account->account_balance)}}</span>
                    </div>
                </div>
            </div>
        @endif

        @if($account->account_credit)
                <div class="card mb-3 p-3 border-dark">
                    <div class="d-flex align-items-center">
                        <div class="avatar rounded-circle no-thumbnail bg-light"><i class="fa fa-exclamation fa-lg"></i></div>
                        <div class="flex-fill ms-3 text-truncate">
                            <div class="small">Credit Balance</div>
                            <span class="h5 mb-0">${{number_format(abs($account->account_credit),2)}}</span>
                        </div>
                    </div>
                </div>
            @endif

        @livewire('admin.activity-component', ['account' => $account])

    </div>


</div>
