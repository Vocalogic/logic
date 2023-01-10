<div class="rightbar card">
    <div class="card-body">
        <ul class="nav nav-tabs tab-card text-center" role="tablist">
            <li class="nav-item flex-fill"><a class="nav-link active" data-bs-toggle="tab" href="#alerts" role="tab">Alerts</a></li>
            <li class="nav-item flex-fill"><a class="nav-link" data-bs-toggle="tab" href="#financials" role="tab">Financials</a></li>
            <li class="nav-item flex-fill"><a class="nav-link" data-bs-toggle="tab" href="#pbx" role="tab">PBX</a></li>
        </ul>
        <div class="card-body custom_scroll">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="alerts" role="tabpanel">
                    @include('admin.accounts.alerts.index')
                </div>

                <div class="tab-pane fade" id="financials" role="tabpanel">
                    @include('admin.accounts.financials.index')
                </div>

                <div class="tab-pane fade" id="pbx" role="tabpanel">
                    @include('admin.accounts.pbx.index')
                </div>

            </div>
        </div>
    </div>
</div>
