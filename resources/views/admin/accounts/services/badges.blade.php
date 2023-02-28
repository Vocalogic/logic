@if($item->quote)
    <a href="/admin/quotes/{{$item->quote->id}}">
        <span class="badge badge-outline-info">
            via quote #{{$item->quote->id}}
        </span>
    </a>
@endif

@if($item->quote && $item->quote->contract_expires)
    <span class="badge badge-outline-primary">
        contracted until {{$item->quote->contract_expires->format('m/d/y')}}
    </span>
@endif

@if($item->frequency != \App\Enums\Core\BillFrequency::Monthly && $item->frequency)
    <span class="badge badge-outline-info">
        {{$item->frequency->getHuman()}} Billing (Bills:
        {{$item->next_bill_date
            ? $item->next_bill_date->format("m/d/y")
            : $account->next_bill?->format("m/d/y")}})
    </span>
@endif

@if($item->remaining)
    <span class="badge badge-outline-primary">
        {{$item->remaining}} payments left
    </span>
@endif
@if($item->terminate_on)
    <span class="badge badge-outline-danger">
        Terminating on {{$item->terminate_on->format("m/d/y")}} - {{$item->terminate_reason}}
    </span>
@endif
@if($item->suspend_on)
    <span class="badge badge-outline-warning">
        Suspending on {{$item->suspend_on->format("m/d/y")}} - {{$item->suspend_reason}}
    </span>
@endif

@if($item->requested_termination_date)
    <span class="badge badge-outline-warning">Customer Requested Termination on
        {{$item->requested_termination_date->format("m/d/y")}} - {{$item->requested_termination_reason}}
    </span>
@endif

@if($item->recurringProfile)
    <span class="badge badge-outline-warning">
        billed separately ({{$item->recurringProfile->name}})
    </span>
@endif
