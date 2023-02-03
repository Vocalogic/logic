<p class="card-text">
    Select the commissions that you wish to batch. If you select more than one agent, then multiple
    batches will be created. A single batch (or payout) will be sent to one individual. Select
    all the commissions that you want to schedule payment for.
</p>

<form method="post" action="/admin/finance/commission_batches">
    @csrf
    @method('POST')


    <table class="table table-sm">
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
        @foreach(\App\Models\Commission::whereNull('commission_batch_id')->get() as $c)
            <tr>
                <td>@if($c->user)
                        {{$c->user->name}}
                    @else
                        {{$c->affiliate->name}} <span class="badge bg-primary">affiliate</span>
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

    <input type="submit" class="btn btn-{{bm()}}primary btn-block w-100 mt-3" value="Create Batch">


</form>
