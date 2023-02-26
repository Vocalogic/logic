<p class="card-text">
    You can edit the items on an existing batch or set the payment status by putting in a date here.
    If you disable a commission from this batch, it will go back to being 'Not Batched'.
</p>
@if(moneyFormat($batch->total) < (int) setting('commissions.min'))
    <div class="alert alert-warning alert-dismissible alert-label-icon rounded-label fade show mb-3" role="alert">
        <i class="ri-alert-line label-icon"></i><strong>Minimum Amount not Met</strong>
        This commission total has not met the minimum amount of <strong>${{number_format(setting('commissions.min'),2)}}</strong> to
        be sent.
    </div>
@endif
    <form method="POST" action="/admin/finance/commission_batches/{{$batch->id}}">
    @csrf
    @method('PUT')
        <div class="row">
            <div class="col-lg-6">
                <div class="form-floating">
                    <input type="date" class="form-control" name="paid_on">
                    <label>Paid On:</label>
                    <span class="helper-text">Leave blank if not paid</span>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-floating">
                    <input type="text" class="form-control" name="transaction_detail">
                    <label>Transaction Detail:</label>
                    <span class="helper-text">Enter check or authorization number of payment</span>
                </div>
            </div>

        </div>

    <div class="row mt-3">
        <div class="col-lg-12 text-center">
            <h5>Commission Payable Total: <b class="text-primary">${{moneyFormat($batch->total)}}</b></h5>
        </div>
    </div>

    <table class="table table-sm mt-3">
        <thead>
        <tr>
            <th>Agent</th>
            <th>Invoice</th>
            <th>Amount</th>
            <th>Scheduled</th>
            <th>Include</th>
        </tr>
        </thead>
        <tbody>
        @foreach($batch->commissions as $c)
            <tr>
                <td>
                    @if($c->user)
                    {{$c->user->name}}
                    @else
                    {{$c->affiliate?->name}}
                    @endif
                </td>
                <td>#{{$c->invoice->id}}</td>
                <td>${{moneyFormat($c->amount)}}</td>
                <td>{{$c->scheduled_on ? $c->scheduled_on->format("m/d/y") : "N/A"}}</td>
                <td>
                    <div class="form-floating">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" {{$c->scheduled_on ? "checked" : null}} role="switch" value="1"
                                   id="c_{{$c->id}}"
                                   name="c_{{$c->id}}">
                        </div>
                    </div>

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <button type="submit" class="btn btn-primary ladda pull-right mt-3">
        <i class="fa fa-save"></i> Save Batch
    </button>
</form>
