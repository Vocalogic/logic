<table class="table table-sm table-striped">
    <thead>
    <tr>
        <th>#</th>
        <th>Invoice</th>
        <th>Account</th>
        <th>Partner/Agent</th>
        <th>Status</th>
        <th>Amount</th>
        <th>Batch</th>
    </tr>
    </thead>
    <tbody>
    @foreach($commissions as $c)
        <tr>
            <td><a class="live" data-title="Edit Commission" href="/admin/finance/commissions/{{$c->id}}"><span
                        class="badge bg-{{bm()}}primary">#{{$c->id}}</span></a></td>
            <td><a href="/admin/invoices/{{$c->invoice->id}}"><span
                        class="badge bg-{{bm()}}info">#{{$c->invoice->id}}</span></a></td>
            <td>{{$c->invoice->account->name}}</td>
            <td>
                @if($c->invoice->account->affiliate)
                    Affiliate / {{$c->invoice->account->affiliate->name}}
                @else
                {{$c->account->name}} / {{$c->invoice->account->agent->short}}
                @endif
            </td>
            <td>{{$c->status->getHuman()}}
                @if($c->status == \App\Enums\Core\CommissionStatus::Scheduled)
                    <br/><span
                        class="badge bg-{{bm()}}info">{{$c->scheduled_on->format("m/d/y")}}</span>
                @endif
                @if($c->edit_note)
                    <br/><small class="text-muted"><i class="fa fa-edit"></i> {{$c->edit_note}}</small>
                @endif
            </td>
            <td>${{moneyFormat($c->amount)}}</td>
            <td>
                @if($c->batch)
                    <a class="live" data-title="Edit Batch #{{$c->batch->id}}"
                       href="/admin/finance/commission_batches/{{$c->batch->id}}">#{{$c->batch->id}}</a>
                @else
                    Not Batched
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
