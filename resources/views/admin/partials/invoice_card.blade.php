<div class="card border card-border-{{$invoice->isPastDue ? 'danger' : 'primary'}}">
    <div class="card-body">
        <div class="d-flex">
            <h6 class="flex-grow-1">Invoice #{{$invoice->id}}</h6>
            <span class="badge d-inline-flex align-items-center
                    justify-content-start bg-{{$invoice->status->getColor()}}">
                        {{$invoice->status}}
                    </span>
        </div>
        <h5 class="font-weight-bold"><b>${{moneyFormat($invoice->balance)}}</b></h5>
        <h6 class="text-muted">
            @if($invoice->isPastDue)
                <span class="text-danger">Due {{$invoice->due_on?->diffInDays()}} days ago</span>
            @else
                Due in {{$invoice->due_on?->diffInDays()}} days
            @endif
        </h6>
    </div>
    <div class="card-footer text-center p-2 bg-gray">
        <a class="text-info" href="/admin/invoices/{{$invoice->id}}">
            View Invoice #{{$invoice->id}} <i class="fa fa-chevron-right"></i>
        </a>
    </div>
</div>
