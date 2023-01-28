<div class="card mt-2">
    <div class="card-body">
        <form method="post" action="/admin/quotes/{{$quote->id}}">
            @method('PUT')
            @csrf
            <h6 class="fw-bold">Quote Settings</h6>
            <div class="row g-3 mb-4">
                <div class="col-lg-12 col-md-12">

                    <div class="form-floating">
                        <input type="text" class="form-control" name="name" value="{{$quote->name}}">
                        <label>Quote Name</label>
                        <span class="helper-text">Easily identifiable name for quote</span>
                    </div>

                    <div class="form-floating mt-2">
                        @if($quote->coterm)
                            {!! Form::select('term', \App\Models\Quote::getTermSelectable(), $quote->coterm->term, ['class' => 'form-control', 'disabled' => true]) !!}
                        @else
                            {!! Form::select('term', \App\Models\Quote::getTermSelectable(), $quote->term, ['class' => 'form-control']) !!}
                        @endif
                        <label>Term Length</label>
                        <span class="helper-text">Select the contract term</span>
                    </div>

                    <div class="form-floating">
                        <input type="text" class="form-control" name="net_terms" value="{{$quote->net_terms}}">
                        <label>Payment NET Terms (in days)</label>
                        <span class="helper-text">Enter days given to pay the bill.</span>
                    </div>

                    <div class="form-floating mt-2">
                        {!! Form::select('preferred', [0 => 'No', 1 => 'Yes'], $quote->preferred, ['class' => 'form-control']) !!}
                        <label>Preferred?</label>
                        <span class="helper-text">The primary quote should be preferred</span>
                    </div>

                    <div class="form-floating">
                        <textarea class="form-control" name="notes" style="height: 100px;">{{$quote->notes}}</textarea>
                        <label>Quote Message</label>
                        <span class="helper-text">Message placed on quote to customer.</span>
                    </div>

                    @if(isset($coterm))
                        <div class="form-floating mt-2">
                            {!! Form::select('coterm_id',
                              array_replace([0 => '-- No Coterm --'], \App\Models\Quote::where('account_id', $quote->account->id)->where('term', '>', 0)->whereNotNull('activated_on')->pluck('name', 'id')->all()), $quote->coterm_id, ['class' => 'form-control']) !!}
                            <label>Co-term Quote?</label>
                            <span class="helper-text">If you would like to terminate a previous contract and create a new one with the same ending date.</span>
                        </div>
                    @endif
                </div>
                <div class="row mt-2">
                    <div class="col-lg-6">
                        <a class="text-danger" data-bs-toggle="modal" href="#declineModal">
                            <i class="fa fa-times"></i> Decline</a>
                    </div>

                    <div class="col-lg-6">
                        <button type="submit" name="submit" class="btn btn-primary ladda pull-right"
                                data-style="zoom-out">
                            <i class="fa fa-save"></i> Save
                        </button>
                    </div>

                </div>
            </div>


        </form>

    </div>
</div>


