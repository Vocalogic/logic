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
