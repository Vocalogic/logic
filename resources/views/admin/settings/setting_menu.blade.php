<div class="list-group list-group-fill-primary mt-3">
    <a class="list-group-item {{$tab == 'brand' ? 'active' : null}}" href="/admin/settings?tab=brand">Brand</a>
    <a class="list-group-item {{$tab == 'lead' ? 'active' : null}}" href="/admin/settings?tab=lead">Leads</a>
    <a class="list-group-item {{$tab == 'quote' ? 'active' : null}}" href="/admin/settings?tab=quote">Quotes</a>
    <a class="list-group-item {{$tab == 'invoice' ? 'active' : null}}" href="/admin/settings?tab=invoice">Invoicing</a>
    <a class="list-group-item {{$tab == 'mail' ? 'active' : null}}" href="/admin/settings?tab=mail">Mail</a>
    <a class="list-group-item {{$tab == 'account' ? 'active' : null}}" href="/admin/settings?tab=account">Accounts</a>
    <a class="list-group-item {{$tab == 'shop' ? 'active' : null}}" href="/admin/settings?tab=shop">Shop</a>
    <a class="list-group-item {{$tab == 'order' ? 'active' : null}}" href="/admin/settings?tab=order">Orders</a>
    <a class="list-group-item {{$tab == 'project' ? 'active' : null}}" href="/admin/settings?tab=project">Projects</a>
</div>

@if(env('LOGIN_BYPASS'))
<div class="alert alert-info mt-5">
    <i class="fa fa-info"></i> The following IP addresses are allowed to login without authentication for support purposes:
    @foreach(explode(",", env('LOGIN_BYPASS')) as $ip)
     <b>{{$ip}}</b>
    @endforeach
</div>
@endif
