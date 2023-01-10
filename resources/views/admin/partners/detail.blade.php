<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs tab-card border-bottom-0 pt-2 fs-6" role="tablist">
                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#accounts" role="tab"
                                            aria-selected="true"><i class="fa fa-users"></i><span
                                class="d-none d-sm-inline-block ms-2">Accounts</span></a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#pending" role="tab"
                                            aria-selected="false"><i class="fa fa-money"></i><span
                                class="d-none d-sm-inline-block ms-2">Pending</span></a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#paid" role="tab"
                                            aria-selected="false"><i class="fa fa-check"></i><span
                                class="d-none d-sm-inline-block ms-2">Paid Invoices</span></a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#commissions" role="tab"
                                            aria-selected="false"><i class="fa fa-group"></i><span
                                class="d-none d-sm-inline-block ms-2">Commission Invoices</span></a></li>
                </ul>


                <div class="tab-content mt-4 mb-4">

                    <div class="tab-pane fade active show" id="accounts" role="tabpanel">
                        @include('admin.partners.partials.accounts')
                    </div>

                    <div class="tab-pane fade" id="pending" role="tabpanel">
                        @include('admin.partners.partials.pending')
                    </div>

                    <div class="tab-pane fade" id="paid" role="tabpanel">
                        @include('admin.partners.partials.paid')
                    </div>
                    <div class="tab-pane fade" id="commissions" role="tabpanel">
                        @include('admin.partners.partials.payouts')
                    </div>


                </div>


            </div>
        </div>
    </div>
</div>
