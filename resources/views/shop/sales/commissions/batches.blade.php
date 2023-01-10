<table class="table table-sm table-striped">
    <thead>
    <tr>
        <th>#</th>
        <th>Paid On</th>
        <th>Transaction</th>
        <th>Notes</th>
        <th>Paid By</th>

    </tr>
    </thead>
    <tbody>
        @foreach(\App\Models\CommissionBatch::where('user_id', user()->id)->get() as $batch)
        <tr>
            <td>#{{$batch->id}}</td>
            <td>{{$batch->paid_on ? $batch->paid_on->format("m/d/y") : "Not Paid"}}</td>
            <td>{{$batch->transaction_detail ?: "N/A"}}</td>
            <td>{{$batch->notes}}</td>
            <td>{{$batch->paidBy ? $batch->paidBy->short : "N/A"}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
