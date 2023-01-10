<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Payouts TO {{$partner->name}}</h5>
                <table class="table mt-3 table-striped table-sm datatable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Paid On</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(\App\Models\PartnerInvoice::where('partner_id', $partner->id)->orderBy('created_at', 'DESC')->get() as $invoice)
                        <tr>
                            <td><a class='live'
                                   data-title='Outgoing Partner Commission #{{$invoice->id}}'
                                   href="/admin/partners/{{$partner->id}}/invoice/{{$invoice->id}}">
                                    #{{$invoice->id}}
                                </a>
                            </td>
                            <td>${{moneyFormat($invoice->total)}}</td>
                            <td>{{$invoice->status}}</td>
                            <td>{{$invoice->paid_on ? $invoice->paid_on->format("m/d/y") : "Unpaid"}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Payouts FROM {{$partner->name}}</h5>
                <table class="table mt-3 table-striped table-sm datatable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Paid On</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($partner->getRemoteCommissions() as $invoice)
                        <tr>
                            <td><a class='live'
                                   data-title='Incoming Partner Commission #{{$invoice->id}}'
                                   href="/admin/partners/{{$partner->id}}/remote/invoice/{{$invoice->id}}">
                                    #{{$invoice->id}}
                                </a>
                            </td>
                            <td>${{moneyFormat($invoice->amount)}}</td>
                            <td>{{$invoice->status}}</td>
                            <td>{{$invoice->paid_on ? \Carbon\Carbon::createFromTimestamp($invoice->paid_on)->format("m/d/y") : "Unpaid"}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
