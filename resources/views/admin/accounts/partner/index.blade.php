<div class="row">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body">

                <p>
                    Update partner commission structure below. This account will have access to their own leads, as well
                    as
                    see quotes generated.
                </p>
                <form method="POST" action="/admin/accounts/{{$account->id}}">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-floating">
                                {!! Form::select('partner_nrc', [0 => 'No', 1 => 'Yes'], $account->partner_nrc, ['class' => 'form-control']) !!}
                                <label>Partner Commissioned on NRC</label>
                                <span
                                    class="helper-text">If commissions should include non-recurring, select yes.</span>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="partner_commission_mrr"
                                       value="{{$account->partner_commission_mrr}}">
                                <label>Commission MRR (percentage)</label>
                                <span class="helper-text">Percentage of MRR to commission monthly.</span>
                            </div>
                        </div>


                        <div class="col-lg-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="partner_commission_spiff"
                                       value="{{$account->partner_commission_spiff}}">
                                <label>SPIFF Months (One Time MRR) </label>
                                <span class="helper-text">Number of months to give for sale (full MRR)</span>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-floating">
                                {!! Form::select('partner_commission_type', ['MRR' => 'MRR', 'SPIFF' => 'SPIFF', 'BOTH' => 'BOTH'], $account->partner_commission_type, ['class' => 'form-control']) !!}
                                <label>Partner Commission Type</label>
                                <span
                                    class="helper-text">Select which commission type to provide to partner.</span>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">


                        <div class="col-lg-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="partner_net_days"
                                       value="{{$account->partner_net_days}}">
                                <label>Partner NET Days to Pay </label>
                                <span class="helper-text">Enter number of days after invoice is paid for partner to get commission.</span>
                            </div>
                        </div>




                        <div class="col-lg-12 mt-2">
                            <input type="submit" name="save" value="Update Partner Profile"
                                   class="btn btn-light-primary wait">
                        </div>
                    </div>
                </form>
            </div>


        </div>
    </div>
    <div class="col-lg-5">
        <h6 class="card-title">Partner Accounts</h6>
        <table class="table table-sm">
            <thead>
            <tr>
                <th>Account</th>
                <th>MRR</th>
            </tr>
            </thead>
            <tbody>
            @foreach(\App\Models\Account::where('partner_id', $account->id)->where('active', true)->get() as $acc)
                <tr>
                    <td><a href="/admin/accounts/{{$acc->id}}">{{$acc->name}}</a></td>
                    <td>${{moneyFormat($acc->mrr)}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <h6 class="card-title pt-3">Commission Assignments</h6>

        <table class="table table-sm datatable">
            <thead>
            <tr>
                <th>#</th>
                <th>Status</th>
                <th>Amount</th>
            </tr>
            </thead>
            <tbody>
            @foreach(\App\Models\Commission::where('account_id', $account->id)->get() as $c)
                <tr>
                    <td>#{{$c->id}}</td>
                    <td>{{$c->status->getHuman()}}</td>
                    <td>${{moneyFormat($c->amount)}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>



    </div>
</div>
