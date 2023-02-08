<div class="text-center">
    <h6 class="card-title">{{$account->name}}</h6>
    @if($account->agent)
        <span class="text-muted">Agent: <b>{{$account->agent->name}}</b></span><br/>
    @endif
    @if($account->affiliate)
        <span class="text-muted fs-7">Affiliate: <b>{{$account->affiliate->name}}</b></span>
    @endif
</div>

<ul class="list-group list-group-custom mt-3">
    <li class="list-group-item {{preg_match("/overview/i", app('request')->getUri()) ? "active" : null}}">
        <a class="color-600" href="/admin/accounts/{{$account->id}}/overview">Overview</a>
    </li>
    <li class="list-group-item {{preg_match("/services/i", app('request')->getUri()) ? "active" : null}}">
        <a class="color-600" href="/admin/accounts/{{$account->id}}/services">Services</a>
    </li>
    <li class="list-group-item {{preg_match("/billing/i", app('request')->getUri()) ? "active" : null}}">
        <a class="color-600" href="/admin/accounts/{{$account->id}}/billing">Billing</a>
    </li>
    <li class="list-group-item {{preg_match("/invoices/i", app('request')->getUri()) ? "active" : null}}">
        <a class="color-600" href="/admin/accounts/{{$account->id}}/invoices">Invoices</a>
    </li>
    <li class="list-group-item {{preg_match("/users/i", app('request')->getUri()) ? "active" : null}}">
        <a class="color-600" href="/admin/accounts/{{$account->id}}/users">Users</a>
    </li>
    <li class="list-group-item {{preg_match("/quotes/i", app('request')->getUri()) ? "active" : null}}">
        <a class="color-600" href="/admin/accounts/{{$account->id}}/quotes">Quotes</a>
    </li>
    <li class="list-group-item {{preg_match("/events/i", app('request')->getUri()) ? "active" : null}}">
        <a class="color-600" href="/admin/accounts/{{$account->id}}/events">Events</a>
    </li>
    <li class="list-group-item {{preg_match("/profile/i", app('request')->getUri()) ? "active" : null}}">
        <a class="color-600" href="/admin/accounts/{{$account->id}}/profile">Profile</a>
    </li>
    <li class="list-group-item {{preg_match("/pricing/i", app('request')->getUri()) ? "active" : null}}">
        <a class="color-600" href="/admin/accounts/{{$account->id}}/pricing">Pricing</a>
    </li>
    <li class="list-group-item {{preg_match("/files/i", app('request')->getUri()) ? "active" : null}}">
        <a class="color-600" href="/admin/accounts/{{$account->id}}/files">Files</a>
    </li>
</ul>

@if($account->account_balance)
    <div class="card mt-3 mb-3 p-3 border-dark">
        <div class="d-flex align-items-center">
            <div class="avatar rounded-circle no-thumbnail bg-light"><i class="fa fa-dollar fa-lg"></i></div>
            <div class="flex-fill ms-3 text-truncate">
                <div class="small">Outstanding Balance</div>
                <span class="h5 mb-0">${{moneyFormat($account->account_balance)}}</span>
            </div>
        </div>
    </div>
@endif

@if($account->parent)
    <div class="card mt-3 mb-3 p-3 border-warning">
        <div class="d-flex align-items-center">
            <div class="avatar rounded-circle no-thumbnail bg-light"><i class="fa fa-building-o fa-lg"></i></div>
            <div class="flex-fill ms-3 text-truncate">
                <div class="small">Parent Account</div>
                <span class="h6 mb-0"><a href="/admin/accounts/{{$account->parent->id}}">{{$account->parent->name}}</a></span>
            </div>
        </div>
    </div>
@endif

@if($account->account_credit)
    <div class="card mb-3 p-3 border-dark">
        <div class="d-flex align-items-center">
            <div class="avatar rounded-circle no-thumbnail bg-light"><i class="fa fa-exclamation fa-lg"></i></div>
            <div class="flex-fill ms-3 text-truncate">
                <div class="small">Credit Balance</div>
                <span class="h5 mb-0">${{number_format(abs($account->account_credit),2)}}</span>
            </div>
        </div>
    </div>
@endif
