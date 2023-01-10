<table class="table mt-3 table-striped">
    <thead>
    <tr>
        <th>Company</th>
        <th>Contact</th>
        <th>Status</th>
        <th>Age</th>
        <th>MRR/NRC</th>
        <th>Commissionable</th>
    </tr>
    </thead>
    <tbody>
        @foreach(\App\Models\Lead::where('active', true)->where('agent_id', user()->id)->get() as $lead)

            <tr>
                <td class="d-flex align-items-center">
                    <img
                        src="{{$lead->logo_id ? _file($lead->logo_id)->relative : "/assets/images/xs/avatar1.jpg"}}"
                        class="avatar" alt="">
                    <a href="/sales/leads/{{$lead->id}}">
                        <div class="ms-2 mb-0 fw-bold">{{$lead->company}}</div>
                    </a>
                </td>
                <td>{{$lead->contact}}</td>
                <td>{{$lead->status ? $lead->status->name : "Unknown"}}</a>
                </td>

                <td>{{$lead->created_at->diffInDays()}} days
                    @if($lead->requires_update)
                        <br/><span class="badge bg-danger">stale</span>
                    @endif
                </td>

                <td><span class="badge bg-light text-dark">${{moneyFormat($lead->primaryMrr)}}</span> / <span
                        class="badge bg-light text-dark">${{moneyFormat($lead->primaryNrc)}}</span></td>
                <td>${{moneyFormat($lead->commissionableAmount)}}</td>

            </tr>
        @endforeach
    </tbody>
</table>
