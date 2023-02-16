<tr {{$obj->presentable ? "class='bg-light-success'" : null}}>
    <td>
        <a href="/admin/quotes/{{$obj->id}}"><span
                class="badge bg-primary">#{{$obj->id}}</span></a>
    </td>
    <td>
        <a href="/admin/quotes/{{$obj->id}}">
            {{$obj->name}}
        </a>
    </td>
    <td>{{$obj->created_at->diffForHumans()}}</td>
    <td>${{moneyFormat($obj->mrr)}}</td>
    <td>${{moneyFormat($obj->nrc)}}</td>
    <td>{{$obj->term ? "$obj->term Months" : "MTM"}}</td>
    <td>${{moneyFormat($obj->totalValue)}} <span
            class="pull-right">{!! $obj->marginBadge !!}</span></td>
    <td>
        <a href="/admin/quotes/{{$obj->id}}/download" class="btn btn-link btn-sm text-muted wait"
           data-bs-toggle="tooltip" data-bs-placement="top" data-anchor=".custom-table"
           title="Download"><i
                class="fa fa-download"></i></a>
        @if($obj->lead)
            <a href="/admin/leads/{{$obj->lead->id}}/quotes/{{$obj->id}}"
               class="btn btn-link btn-sm text-muted"
               data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
        @else
            <a href="/admin/accounts/{{$obj->account->id}}/quotes/{{$obj->id}}"
               class="btn btn-link btn-sm text-muted"
               data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
        @endif

        @if($obj->presentable)
            <a href="/admin/quotes/{{$obj->id}}/presentable" class="btn btn-link btn-sm text-muted"
               data-bs-toggle="tooltip" data-bs-placement="top" title="Quote is Presentable"><i
                    class="fa fa-check"></i></a>
        @else
            <a href="/admin/quotes/{{$obj->id}}/presentable" class="btn btn-link btn-sm text-muted"
               data-bs-toggle="tooltip" data-bs-placement="top" title="Quote is Not Presentable"><i
                    class="fa fa-exclamation-triangle"></i></a>
        @endif

        <a href="/admin/quotes/{{$obj->id}}/send" class="btn btn-link btn-sm text-muted confirm"
           data-method="GET"
           data-message="Are you sure you want to send this quote?"
           data-bs-toggle="tooltip" data-bs-placement="top" title="Send Quote"><i
                class="fa fa-mail-forward"></i>
        </a>

        <a href="/admin/quotes/{{$obj->id}}" class="btn btn-link btn-sm text-muted confirm"
           data-method="DELETE"
           data-message="Are you sure you want to delete this quote?"
           data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Quote"><i
                class="fa fa-trash"></i>
        </a>
    </td>
</tr>
