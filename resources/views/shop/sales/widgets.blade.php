<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    Commission MRC (Services)
                </h5>
                <p>
                    {{user()->agent_comm_mrc}}%
                </p>
            </div>
        </div>
    </div>


    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    Commission SPIFF
                </h5>
                <p>
                    {{user()->agent_comm_spiff}} x MRR
                </p>
            </div>
        </div>
    </div>

</div>
