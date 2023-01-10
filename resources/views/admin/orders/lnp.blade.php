<div class="card">
    <div class="card-body">
        <h6 class="card-title">LNP Orders
            <a href="/admin/lnp_orders/create?fromOrder={{$order->id}}"><i class="fa fa-plus text-primary"></i></a>
        </h6>

        @if(!$order->lnps()->count())
            <div class="card mt-3">
                <div class="card-body text-center">
                    <img src="/assets/images/no-data.svg" class="w120" alt="No Data">
                    <div class="mt-4 mb-3">
                        <span class="text-muted">No Port Orders Found</span>
                    </div>
                    <a class="btn btn-{{bm()}}primary border lift" href="/admin/lnp_orders/create?fromOrder={{$order->id}}"><i class="fa fa-plus"></i> New
                        LNP Order</a>
                </div>
            </div>
        @else
            <table class="table table-sm">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Provider</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($order->lnps as $lnp)
                    <tr>
                        <td><a href="/admin/lnp_orders/{{$lnp->id}}"><span class="badge bg-{{bm()}}primary">#{{$lnp->id}}</span></a></td>
                        <td>{{$lnp->provider ? $lnp->provider->name : "Not Selected"}}</td>
                        <td>{{$lnp->status->value}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>


        @endif


    </div>

</div>
