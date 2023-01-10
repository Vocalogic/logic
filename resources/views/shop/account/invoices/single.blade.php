<section class="user-dashboard-section section-b-space" style="padding:0px;">
    <div class="dashboard-right-sidebar">
        <div class="dashboard-home">
            <div class="total-box">
                <div class="row g-sm-4 g-3">
                    @foreach($invoices as $invoice)

                        <div class="col-xxl-4 col-lg-4 col-md-12 col-sm-12">
                            <a href="/shop/account/invoices/{{$invoice->id}}">
                                <div class="totle-contain">
                                    <img src="/ec/assets/images/svg/pending.svg"
                                         class="img-1 blur-up lazyloaded" alt="">
                                    <img src="/ec/assets/images/svg/pending.svg" class="blur-up lazyloaded"
                                         alt="">
                                    <div class="totle-detail">
                                        <h5>Invoice #{{$invoice->id}}</h5>
                                        <h3>
                                            @if($invoice->balance == 0)
                                                <span class="text-success">PAID ({{$invoice->paid_on ? $invoice->paid_on->format("m/d/y"): null}})</span>
                                            @else
                                                <span class="text-warning">Balance: ${{moneyFormat($invoice->balance)}}</span>
                                            @endif
                                        </h3>
                                        @if($invoice->balance > 0)
                                            <h6><small class="text-muted">Due: {{$invoice->due_on->format("F d, Y")}}</small></h6>
                                            @else
                                            <h6><small class="text-muted">Paid On: {{$invoice->paid_on->format("F d, Y")}}</small></h6>
                                        @endif

                                    </div>
                                </div>
                            </a>
                        </div>

                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
