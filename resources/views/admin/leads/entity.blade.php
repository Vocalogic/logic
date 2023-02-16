<tr>
    <td class="d-flex align-items-center">
        <img
            src="{{$obj->logo_id ? _file($obj->logo_id)->relative : "/assets/images/xs/avatar1.jpg"}}"
            class="avatar" alt="">
        <a href="/admin/leads/{{$obj->id}}">
            <div class="ms-2 mb-0 fw-bold">{{$obj->company}}</div>
        </a>
    </td>
    <td>{{$obj->contact}}</td>
    <td>{{$obj->status ? $obj->status->name : "Unknown"}}</a>
    </td>

    <td>{{$obj->type ? $obj->type->name : "Unassigned"}}</td>
    <td>{{$obj->age}} days
        @if($obj->requires_update)
            <br/><span class="badge bg-danger">stale</span>
        @endif
    </td>
    <td>{{$obj->agent?->short}}</td>
    <td><span class="badge bg-light text-dark">${{moneyFormat($obj->primaryMrr)}}</span> / <span
            class="badge bg-light text-dark">${{moneyFormat($obj->primaryNrc)}}</span></td>

</tr>
