<div class="item card ribbon">
    <div class="option-8 position-absolute text-light">
        @if($invoice->isPastDue)
            <i class="fa fa-exclamation-circle"></i>
        @else
            <i class="fa fa-info"></i>
        @endif
    </div>
    <div class="card-body">
        <div class="avatar lg rounded-circle no-thumbnail mb-3 fs-5"><a
                href="/admin/accounts/{{$invoice->account->id}}/invoices/{{$invoice->id}}">#{{$invoice->id}}</a></div>
        <small class="text-muted">Balance</small>
        <h4>${{moneyFormat($invoice->balance)}}</h4>
        <ul class="lh-lg mb-0 text-muted list-unstyled">
            <li>Due: {{$invoice->due_on->format("m/d/y")}} <Br/>({{$invoice->due_on->diffForHumans()}})</li>
            <li>Total: ${{moneyFormat($invoice->total)}}</li>
        </ul>

        <div class="text-center">
            <a class="btn btn-link btn-sm color-400"
               href="/admin/accounts/{{$invoice->account->id}}/invoices/{{$invoice->id}}" data-bs-toggle="tooltip"
               data-bs-placement="top" title="" data-bs-original-title="View Invoice" aria-label="Edit"><i
                    class="fa fa-pencil"></i></a>

            <a class="confirm btn btn-link btn-sm color-400"
               data-method="GET"
               data-message="Are you ready to send this invoice?"
               data-bs-toggle="tooltip" data-bs-original-title="Send Invoice"
               href="/admin/invoices/{{$invoice->id}}/send"><i class="fa fa-send"></i>
            </a>

            <a class="btn btn-link btn-sm color-400 wait" href="/admin/invoices/{{$invoice->id}}/download"><i
                    class="fa fa-download"></i></a>
            @if($invoice->transactions()->count() == 0)
                <a class="confirm btn btn-link btn-sm color-400" href="/admin/invoices/{{$invoice->id}}"
                   data-method="DELETE"
                   data-message="Are you sure you watn to permanently delete this invoice?"
                ><i class="fa fa-trash"></i>
                </a>
            @endif


        </div>

    </div>
</div>
