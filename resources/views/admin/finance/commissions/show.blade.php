<form method="POST" action="/admin/finance/commissions/{{$commission->id}}">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-lg-4">
            <div class="form-floating">
                <input type="text" class="form-control" name="amount" value="{{moneyFormat($commission->amount)}}">
                <label>Commission Amount:</label>
                <span class="helper-text">Enter adjustment for commission</span>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="form-floating">
                <textarea class="form-control" name="edit_note">{{$commission->edit_note}}</textarea>
                <label>Edit Note:</label>
                <span class="helper-text">If you are changing the amount you must enter a reason.</span>
            </div>
        </div>
    </div>

    @if(!$commission->commission_batch_id)
        <div class="row mt-3">
            <div class="col-lg-12">
                <p>
                    This commission has not been batched. If you want to append this to an existing batch you can
                    select one here. Otherwise, create a new batch and select this commission.
                </p>
                <div class="form-floating">
                    {!! Form::select('commission_batch_id', \App\Models\CommissionBatch::selectable(), null, ['class' => 'form-control']) !!}
                    <label>Append to Batch:</label>
                    <span class="helper-text">Select a batch to append this item to.</span>
                </div>

            </div>
        </div>
    @endif

    <div class="row mt-3">
        <div class="col-lg-12">
            <input type="submit" class="btn btn-{{bm()}}primary w-100 btn-block" value="Save Commission">
        </div>
    </div>
</form>
