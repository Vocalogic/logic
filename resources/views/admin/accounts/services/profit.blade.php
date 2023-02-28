<div class="card mb-3 mt-2">

    <div class="card-body">
        <h5>Remaining Profit Analysis</h5>
        <p class="card-text mt-2">The following will show your profit margin and estimated remaining
            revenue based on contracted and uncontracted revenue per service.
        </p>
        <span class="h2 d-block mb-3 text-info text-center">${{moneyFormat($account->analysis->remaining)}}</span>

        <div class="table-responsive">
            <table class="table table-sm table-nowrap mb-0">
                <tbody>

                <tr>
                    <td>Total Projected <br/>
                        <small class="text-muted fs-7">Total value of account</small>
                    </td>
                    <td>${{moneyFormat($account->analysis->total)}}</td>
                </tr>

                <tr>
                    <td>Total Opex <br/>
                        <small class="text-muted fs-7">Total cost of account</small>
                    </td>
                    <td>${{moneyFormat($account->analysis->opex)}}</td>
                </tr>

                <tr>
                    <td>Total Invoiced <br/>
                        <small class="text-muted fs-7">Total amount invoiced to date</small>
                    </td>
                    <td>${{moneyFormat($account->analysis->invoiced)}}</td>
                </tr>

                <tr>
                    <td>Projected Remaining Value <br/>
                        <small class="text-muted fs-7">Total projected remaining value</small>
                    </td>
                    <td>${{moneyFormat($account->analysis->remaining)}}</td>
                </tr>
                @foreach($account->analysis->services as $service)
                    <tr>
                        <td>{{$service->service->name}} <br/>
                            <small class="text-muted fs-7">Remaining Value: ({{$service->barPerc}}%)</small>
                            <div class="progress rounded-pill mb-2" style="height: 4px;">
                                <div class="progress-bar {{$service->barColor}}" role="progressbar" style="width: {{$service->barPerc}}%" aria-valuenow="{{$service->barPerc}}" aria-valuemin="0"
                                     aria-valuemax="{{$service->total}}">
                                </div>
                            </div>

                        </td>
                        <td>${{moneyFormat($service->remaining)}}</td>
                    </tr>
                @endforeach


                </tbody>
            </table>
        </div>


    </div>
</div>
