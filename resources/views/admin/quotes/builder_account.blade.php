<div class="row mt-3">
    <div class="col-lg-3">
        <ul class="nav nav-tabs tab-card" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#profitQuote" role="tab">Profit
                    ({{$quote->analysis->margin}}%)</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#settingsQuote"
                                    role="tab">Settings</a></li>

        </ul>
        <div class="tab-content">
            <div class="tab-pane fade" id="settingsQuote" role="tabpanel">
                @if(!$quote->activated_on)
                    @include('admin.quotes.settings', ['coterm' => true])
                @else
                    <div class="alert bg-{{bm()}}warning mt-3"><i class="fa fa-exclamation-circle"></i> This quote has
                        already been accepted.
                    </div>
                @endif
            </div>
            <div class="tab-pane fade show active" id="profitQuote" role="tabpanel">
                @include('admin.quotes.stat')
            </div>
        </div>

        @if($quote->presentable && $quote->lead)
            <div role="alert" class="alert alert-info mt-2">NOTE: This quote has been marked presentable and is
                available
                on the customer's discovery page.
            </div>
        @endif

        @if($quote->presentable && $quote->account && $quote->edtitable)
            <div role="alert" class="alert alert-info mt-2">NOTE: This quote has been marked presentable and is
                available
                on the customer's portal.
            </div>
        @endif


    </div>

    <div class="col-lg-9 rightpanel">
        <div class="btn-group mb-2" role="group" aria-label="Group">

            @if($quote->items()->count())
                <a class="btn btn-outline-info confirm" href="/admin/quotes/{{$quote->id}}/send"
                   data-message="Are you sure you want to send this quote? This will also mark this quote as 'presentable' and will be available for review in the contact's discovery page."
                   data-method="GET"><i class="fa fa-send"></i> Send Quote</a>
                <a class="btn btn-outline-info wait" data-anchor=".rightpanel"
                   href="/admin/quotes/{{$quote->id}}/download"><i
                        class="fa fa-download"></i> Download</a>
            @if($quote->activated_on)
                    <a class="btn btn-outline-info wait" data-anchor=".rightpanel"
                       href="/admin/quotes/{{$quote->id}}/msa"><i
                            class="fa fa-cloud"></i> Download Contract</a>
            @endif
                @if($quote->editable)
                    @if(!$quote->presentable)
                        <a class="btn btn-outline-danger" href="/admin/quotes/{{$quote->id}}/presentable"><i
                                class="fa fa-exclamation"></i> Not Presentable</a>
                    @else
                        <a class="btn btn-outline-success" href="/admin/quotes/{{$quote->id}}/presentable"><i
                                class="fa fa-check"></i> Presentable</a>

                    @endif
                    @if($quote->coterm)
                        <a class="btn btn-light-info confirm" href="/admin/quotes/{{$quote->id}}/coterm/execute"
                           data-message="This will execute this quote, terminate the previous contract, remove active services bound to existing contract and replace services with this quote. Are you sure you want to do this?"
                           data-method="GET"><i class="fa fa-send"></i> Execute Coterm Quote</a>
                    @endif
                @endif

            @endif

            @if($quote->account)
                <a class="btn btn-outline-primary" href="/admin/accounts/{{$quote->account->id}}?active=quotes"><i
                        class="fa fa-arrow-left"></i> Back to Quotes</a>
            @endif
        </div>


        @if($quote->analysis->margin < setting('quotes.margin'))
            <div role="alert" class="alert alert-danger">WARNING: This quote does not meet the minimum profit margin
                of {{setting('quotes.margin')}}% to be a viable deal.
            </div>
        @endif

        @include('admin.quotes.build.quote_items')


    </div>
</div>
@include('admin.quotes.build.add_modals')
