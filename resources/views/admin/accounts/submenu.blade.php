<div class="text-center">
    <h6 class="card-title">{{$account->name}}</h6>
    @if($account->agent)
        <span class="text-muted">Agent: <b>{{$account->agent->name}}</b></span><br/>
    @endif
    @if($account->affiliate)
        <span class="text-muted fs-7">Affiliate: <b>{{$account->affiliate->name}}</b></span>
    @endif
</div>

<div class="list-group list-group-fill-primary mt-3">
    <a class="list-group-item {{preg_match("/overview/i", app('request')->getUri()) ? "active" : null}}"
       href="/admin/accounts/{{$account->id}}/overview">Overview</a>
    <a class="list-group-item {{preg_match("/services/i", app('request')->getUri()) ? "active" : null}}"
       href="/admin/accounts/{{$account->id}}/services">Services</a>
    <a class="list-group-item {{preg_match("/billing/i", app('request')->getUri()) ? "active" : null}}"
       href="/admin/accounts/{{$account->id}}/billing">Billing</a>
    <a class="list-group-item {{preg_match("/invoices/i", app('request')->getUri()) ? "active" : null}}"
       href="/admin/accounts/{{$account->id}}/invoices">Invoices</a>
    <a class="list-group-item {{preg_match("/users/i", app('request')->getUri()) ? "active" : null}}"
       href="/admin/accounts/{{$account->id}}/users">Users</a>
    <a class="list-group-item {{preg_match("/quotes/i", app('request')->getUri()) ? "active" : null}}"
       href="/admin/accounts/{{$account->id}}/quotes">Quotes</a>
    <a class="list-group-item {{preg_match("/events/i", app('request')->getUri()) ? "active" : null}}"
       href="/admin/accounts/{{$account->id}}/events">Events</a>
    <a class="list-group-item {{preg_match("/profile/i", app('request')->getUri()) ? "active" : null}}"
       href="/admin/accounts/{{$account->id}}/profile">Profile</a>
    <a class="list-group-item {{preg_match("/pricing/i", app('request')->getUri()) ? "active" : null}}"
       href="/admin/accounts/{{$account->id}}/pricing">Pricing</a>
    <a class="list-group-item {{preg_match("/files/i", app('request')->getUri()) ? "active" : null}}"
       href="/admin/accounts/{{$account->id}}/files">Files</a>

</div>
