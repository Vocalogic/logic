<div class="row">
    @foreach($account->quotes()->whereNotNull('activated_on')->get() as $quote)
        <div class="col-lg-3 col-xs-12 mt-3">
            @include('admin.accounts.quotes.quote_card', ['quote' => $quote])
        </div>
    @endforeach
</div>
