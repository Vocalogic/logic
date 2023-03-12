<div class="card mb-3 mt-2">

    <div class="card-body">
        <h6 class="card-title m-0">Cost/Profit Analysis</h6>
        <p class="card-text" mt-2>The following will show your profit margin and ensuring that
            the deal meets minimum profit expectations.</p>
        <span class="h2 d-block mb-3">${{moneyFormat($quote->analysis->profit,2)}}</span>
        <!-- Progress -->
        <div class="progress animated-progress mb-2">
            <div class="progress-bar  {{$quote->bar->color}}" role="progressbar" style="width: {{$quote->bar->width}}%" aria-valuenow="{{$quote->bar->width}}" aria-valuemin="0"
                 aria-valuemax="{{setting('quotes.margin')}}">
            </div>
        </div>

        <div class="d-flex justify-content-between mb-4">
            <span>0%</span>
            <span>{{setting('quotes.margin')}}%</span>
        </div>
        <!-- End Progress -->

        @if(!$quote->term)
            <div role="alert" class="alert border-secondary">This quote is configured for <strong>month-to-month</strong>.
                A {{setting('quotes.assumedTerm')}} month lifespan has been assigned for deal metrics.
            </div>
        @endif
        <div class="table-responsive">
            <table class="table table-sm table-nowrap mb-0">
                <tbody>

                <tr>
                    <td>Gross Income <br/>
                        <small class="text-muted">Value before expenses</small>
                    </td>
                    <td>${{moneyFormat($quote->analysis->gross)}}</td>

                </tr>
                <tr>
                    <td>Capital Expenses<br/>
                        <small class="text-muted">Hardware/Capex Purchases</small></td>
                    <td>${{moneyFormat($quote->analysis->capex)}}</td>
                </tr>
                <tr>
                    <td>Operational Expenses<br/>
                        <small class="text-muted">Combined Monthly COGS</small>
                    </td>
                    <td>${{moneyFormat($quote->analysis->opex)}}</td>
                </tr>
                @if($quote->analysis->totalCommission)
                <tr>
                    <td>Total Commission<br/>
                        <small class="text-muted">Total Commission over Term</small>
                    </td>
                    <td>${{moneyFormat($quote->analysis->totalCommission)}}</td>
                </tr>
                <tr>
                    <td>Monthly Commission<br/>
                        <small class="text-muted">Agent Payout per Month</small>
                    </td>
                    <td>${{moneyFormat($quote->analysis->monthlyCommission)}}</td>
                </tr>
                @endif
                @if($quote->analysis->agentSpiff)
                    <tr>
                        <td>Total SPIFF Payout<br/>
                            <small class="text-muted">Total MRR Instant Payout</small>
                        </td>
                        <td>${{moneyFormat($quote->analysis->agentSpiff)}}</td>
                    </tr>
                @endif



                    <tr>
                    <td>Net Value<br/>
                        <small class="text-muted">Deal Value after Expenses</small>
                    </td>
                    <td>${{moneyFormat($quote->analysis->profit)}}</td>
                </tr>
                <tr>
                    <td>Margin
                        <br/>
                        <small class="text-muted">Net Profit Margin</small>
                    </td>
                    <td>{{$quote->analysis->margin}}%<br/>{!! $quote->marginBadge !!}</td>
                </tr>

                </tbody>
            </table>
        </div>

    </div>

</div>

