<ul class="list-group list-group-custom">
    <li class="list-group-item {{$tab == 'brand' ? 'active' : null}}"><a class="color-600" href="/admin/settings?tab=brand">Brand</a></li>
    <li class="list-group-item {{$tab == 'lead' ? 'active' : null}}"><a class="color-600" href="/admin/settings?tab=lead">Leads</a></li>
    <li class="list-group-item {{$tab == 'quote' ? 'active' : null}}"><a class="color-600" href="/admin/settings?tab=quote">Quotes</a></li>
    <li class="list-group-item {{$tab == 'invoice' ? 'active' : null}}"><a class="color-600" href="/admin/settings?tab=invoice">Invoicing</a></li>
    <li class="list-group-item {{$tab == 'mail' ? 'active' : null}}"><a class="color-600" href="/admin/settings?tab=mail">Mail</a></li>
    <li class="list-group-item {{$tab == 'account' ? 'active' : null}}" ><a class="color-600" href="/admin/settings?tab=account">Accounts</a></li>
    <li class="list-group-item {{$tab == 'shop' ? 'active' : null}}"><a class="color-600" href="/admin/settings?tab=shop">Shop</a></li>
    <li class="list-group-item {{$tab == 'order' ? 'active' : null}}"><a class="color-600" href="/admin/settings?tab=order">Orders</a></li>
</ul>

@if(env('LOGIN_BYPASS'))
<div class="alert alert-info mt-5">
    <i class="fa fa-info"></i> The following IP addresses are allowed to login without authentication for support purposes:
    @foreach(explode(",", env('LOGIN_BYPASS')) as $ip)
     <b>{{$ip}}</b>
    @endforeach
</div>
@endif
