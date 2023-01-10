<div class="row">
    @if($item->quote && $item->quote->signature)
        <div class="col-lg-6">
            <div class="form-floating">
                <input type="date" class="form-control" name="contract_expires"
                       value="{{$item->quote->contract_expires?->format("Y-m-d")}}">
                <label>Contract End Date</label>
                <span class="helper-text">Change Contract End Date.</span>
            </div>
        </div>
        <div class="col-lg-6">
            <a href="/admin/accounts/{{$item->account->id}}/services/{{$item->id}}/remcontract"
               class="btn btn-{{bm()}}danger confirm" data-method="GET"
               data-message="Are you sure you want to remove this item from its contract?"
            ><i class="fa fa-times"></i> Remove from Contract</a>
        </div>

    @endif

    @if(!$item->quote)
        <div class="col-md-12 col-12">
            <div class="form-floating">
                {!! Form::select('contract_quote_id', $item->account->getContractedQuotes(), $item->quote_id, ['class' => 'form-control']) !!}
                <label>Select Contract to Apply Item</label>
                <span class="helper-text">Select the contract to apply this item to.</span>
            </div>
        </div>

    @endif
</div>
