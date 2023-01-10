<div class="card mt-3">
    <div class="card-body">

        <table class="table align-middle datatable">
            <thead>
            <tr>
                <th>Company</th>
                <th>Contact</th>
                <th>Status</th>
                <th>Type</th>
                <th>Age</th>
                <th>Agent</th>
                <th>MRR/NRC</th>
                @if($hasPartners)
                    <th>Partner</th>
                @endif
            </tr>
            </thead>
            <tbody>
            @foreach($leads as $lead)
                <tr>
                    <td class="d-flex align-items-center">
                        <img
                            src="{{$lead->logo_id ? _file($lead->logo_id)->relative : "/assets/images/xs/avatar1.jpg"}}"
                            class="avatar" alt="">
                        <a href="/admin/leads/{{$lead->id}}">
                            <div class="ms-2 mb-0 fw-bold">{{$lead->company}}</div>
                        </a>
                    </td>
                    <td>{{$lead->contact}}</td>
                    <td>{{$lead->status ? $lead->status->name : "Unknown"}}</a>
                    </td>

                    <td>{{$lead->type ? $lead->type->name : "Unassigned"}}</td>
                    <td>{{$lead->created_at->diffInDays()}} days
                        @if($lead->requires_update)
                            <br/><span class="badge bg-danger">stale</span>
                        @endif
                    </td>
                    <td>{{$lead->agent?->short}}</td>
                    <td><span class="badge bg-light text-dark">${{moneyFormat($lead->primaryMrr)}}</span> / <span
                            class="badge bg-light text-dark">${{moneyFormat($lead->primaryNrc)}}</span></td>
                    @if($hasPartners)
                        <td>
                            @if($lead->partner && $lead->partner_sourced)
                                <i class="fa fa-arrow-left"></i>
                            @elseif($lead->partner && !$lead->partner_sourced)
                                <i class="fa fa-arrow-right"></i>
                            @endif
                            @if($lead->partner)
                                <a href="/admin/partners/{{$lead->partner_id}}">{{$lead->partner->name}}</a>
                            @else
                                Internal
                            @endif
                        </td>
                    @endif
                </tr>
            @endforeach


            </tbody>
        </table>

    </div>
</div>
