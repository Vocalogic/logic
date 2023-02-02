<ul class="nav nav-tabs tab-card" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#profit" role="tab">Profit ({{$account->analysis->margin}}%)</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#serviceAction" role="tab">Actions</a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane  active" id="profit" role="tabpanel">
        @include('admin.accounts.services.profit')
    </div>

    <div class="tab pane fade" id="serviceAction" role="tabpanel">
        <div class="row">
            <div class="col-lg-12">
                <a class="btn w-100 btn-{{bm()}}primary wait mb-3 mt-3" href="/admin/accounts/{{$account->id}}/statement"><i
                        class="fa fa-download"></i> Download Statement</a>
                <a class="btn w-100 btn-{{bm()}}primary live mb-3" data-title="Schedule Service Suspension"
                   href="/admin/accounts/{{$account->id}}/suspend"><i
                        class="fa fa-clock-o"></i> Schedule Service Suspension</a>
                <a class="btn w-100  btn-{{bm()}}primary live mb-3" data-title="Schedule Service Termination"
                   href="/admin/accounts/{{$account->id}}/terminate"><i
                        class="fa fa-remove"></i> Schedule Service Termination</a>
            </div>
        </div>

    </div>



</div>
