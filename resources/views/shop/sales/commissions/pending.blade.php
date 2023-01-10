<table class="table table-sm table-striped">
    <thead>
    <tr>
        <th>#</th>
        <th>Invoice</th>
        <th>Account</th>
        <th>Status</th>
        <th>Amount</th>
        <th>Batch</th>
    </tr>
    </thead>
    <tbody>
    @foreach($commissions as $c)
        <tr>
            <td><span class="badge bg-primary">#{{$c->id}}</span></td>
            <td><span class="badge bg-info">#{{$c->invoice->id}}</span></td>
            <td>{{$c->invoice->account->name}}</td>
            <td>{{$c->status->getHuman()}}
                @if($c->status == \App\Enums\Core\CommissionStatus::Scheduled)
                    <br/><span
                        class="badge bg-info">{{$c->scheduled_on->format("m/d/y")}}</span>
                @endif
                @if($c->edit_note)
                    <br/><small class="text-muted"><i class="fa fa-edit"></i> {{$c->edit_note}}</small>
                @endif
            </td>
            <td>${{moneyFormat($c->amount)}}</td>
            <td>
                @if($c->batch)
                    #{{$c->batch->id}}
                @else
                    Not Batched
                @endif
            </td>


        </tr>
    @endforeach
    </tbody>
</table>
