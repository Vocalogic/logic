<div class="row mt-3">
    <div class="col-lg-3">
        <ul class="nav nav-tabs tab-card" role="tablist">

            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#profit" role="tab">Profit
                    ({{$quote->analysis->margin}}%)</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#settings"
                                    role="tab">Settings</a></li>

        </ul>
        <div class="tab-content">
            <div class="tab-pane fade" id="settings" role="tabpanel">
                @include('admin.quotes.settings')
            </div>
            <div class="tab-pane fade show active" id="profit" role="tabpanel">
                @include('admin.quotes.stat')
            </div>
        </div>

        @if($quote->presentable)
            <div role="alert" class="alert border-info mt-2">NOTE: This quote has been marked presentable and is
                available on the customer's pre-sales dashboard.
            </div>
        @endif

    </div>

    <div class="col-lg-9 rightpanel">
        @if($quote->items()->count())
            <div class=" mb-2">
                <a class="btn btn-{{bm()}}info confirm" href="/admin/quotes/{{$quote->id}}/send"
                   data-message="Are you sure you want to send this quote? This will also mark this quote as 'presentable' and will be available for review in the contact's discovery page."
                   data-method="GET"><i class="fa fa-send"></i> Send Quote</a>
                <a class="btn btn-{{bm()}}info wait" data-anchor=".rightpanel"
                   href="/admin/quotes/{{$quote->id}}/download"><i class="fa fa-download"></i> Download</a>
                @if(!$quote->presentable)
                    <a class="btn btn-{{bm()}}danger" href="/admin/quotes/{{$quote->id}}/presentable"><i
                            class="fa fa-exclamation"></i> Not Presentable</a>
                @else
                    <a class="btn btn-{{bm()}}success" href="/admin/quotes/{{$quote->id}}/presentable"><i
                            class="fa fa-check"></i> Presentable</a>

                @endif
            </div>
        @endif
        @if($quote->analysis->margin < setting('quotes.margin'))
            <div role="alert" class="alert border-danger">
                <i class="fa fa-exclamation-triangle"></i> WARNING: This quote does not meet the minimum profit margin
                of {{setting('quotes.margin')}}% to be a viable deal.
            </div>
        @endif

        @include('admin.quotes.build.quote_items')

    </div>
</div>

@include('admin.quotes.build.add_modals')


