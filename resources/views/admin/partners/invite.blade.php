<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Create Invitation to {{$partner->name}}</h5>
                <p class="card-text">
                    You have not created an invitation yet. First you must define the commission structure
                    that you would provide to the partner. If this is not applicable, you may leave the
                    following commission fields set to <code>0</code>.
                </p>
                <form method="POST" action="/admin/partners/{{$partner->id}}?createInvite=true">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="commission_out_mrc" value="0">
                                <label>Commission of MRR (in percent)</label>
                                <span class="helper-text">Example (20) would give partner 20% in MRR (monthly commission)</span>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="commission_out_spiff" value="0">
                                <label>Commission SPIFF (x MRR)</label>
                                <span class="helper-text">Example (2) would give partner 2 x MRR (no monthly commission)</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-lg-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="net_days" value="30">
                                <label>Payout Given NET Days from Customer Payment</label>
                                <span class="helper-text">Example (30) days means you will pay 30 days from when your customer pays.</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <div class="form-floating">
                                <textarea class="form-control" style="height:80px;" name="note"></textarea>
                                <label>Note to Partner</label>
                                <span class="helper-text">Enter a note to the partner during the invitation</span>
                            </div>
                        </div>
                        <input type="submit" class="btn btn-{{bm()}}primary wait mt-3" value="Submit Invitation">
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
