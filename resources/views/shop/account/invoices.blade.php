@if($account->getActiveInvoices()->count() == 0)
    You have no unpaid invoices.
@else

    <div class="total-box">
        <div class="row g-sm-4 g-3">

            @foreach($account->getActiveInvoices() as $invoice)

                <div class="col-xxl-12 col-lg-12 col-md-12 col-sm-12 mb-3">
                    <a href="/shop/account/invoices/{{$invoice->id}}">
                        <div class="totle-contain">
                            <img src="/ec/assets/images/svg/pending.svg"
                                 class="img-1 blur-up lazyloaded" alt="">
                            <img src="/ec/assets/images/svg/pending.svg" class="blur-up lazyloaded"
                                 alt="">
                            <div class="totle-detail">
                                <h5>Invoice #{{$invoice->id}}</h5>
                                <h3>
                                    Balance: ${{moneyFormat($invoice->balance)}}
                                </h3>
                            </div>
                        </div>
                    </a>
                </div>

            @endforeach
        </div>


    </div>


@endif
