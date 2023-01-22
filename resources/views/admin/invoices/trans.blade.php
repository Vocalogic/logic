<div class="card mt-3">
    <div class="card-body">
        <p class="card-title">Transactions on Invoice #{{$invoice->id}}</p>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th class="d-none d-lg-block">ID</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Type</th>
                    <th>Details</th>
                </tr>
                </thead>
                <tbody>
                @foreach($invoice->transactions as $trans)
                    <tr>
                        <td class="d-none d-lg-block">
                            {{$trans->local_transaction_id}}
                            @if($trans->remote_transaction_id)
                                <br/>
                                <small class="text-muted">{{$trans->remote_transaction_id}}</small>
                            @endif
                        </td>
                        <td>{{$trans->created_at->format("m/d/y h:ia")}}</td>
                        <td>${{moneyFormat($trans->amount)}}</td>
                        <td>{{$trans->method}}</td>
                        <td>{{$trans->details}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
