<a href="/admin/finance/commission_batches/create" data-title="Create new Commission Batch"
   class="btn btn-block btn-primary w-100 live mb-3">
    <i class="fa fa-plus"></i> Create new Batch
</a>


<div class="card mt-4 mb-3">
    <h6 class="card-title mb-3 pt-2 text-center fs-6">Status</h6>
    <ul class="list-group list-group-custom">
        <li class="list-group-item d-flex justify-content-between">
            <a class="color-600" href="/admin/finance/commissions">Active</a>
            <span class="badge bg-info">
                {{\App\Models\Lead::where('active', true)->count()}}
            </span>
        </li>
        @foreach(\App\Enums\Core\CommissionStatus::cases() as $status)
            <li class="list-group-item d-flex justify-content-between">
                <a class="color-600" href="/admin/finance/commissions?status={{$status->value}}">
                    {{$status->getHuman()}}
                </a>
                    <span class="badge bg-primary">{{$status->count()}}</span>

            </li>
        @endforeach
    </ul>

</div>

<div class="card mt-4">
        <h6 class="card-title mb-3 pt-2 text-center fs-6">Agent</h6>
        <ul class="list-group list-group-custom">
            @foreach(\App\Models\User::where('agent_comm_mrc', '>', 0)->get() as $user)
                <li class="list-group-item d-flex justify-content-between">
                    <a class="color-600" href="/admin/finance/commissions?byUser={{$user->id}}">
                        {{$user->name}}</a>
                </li>
            @endforeach
        </ul>
</div>


<div class="card mt-4">
    <h6 class="card-title mb-3 pt-2 text-center fs-6">Affiliate</h6>
    <ul class="list-group list-group-custom">
        @foreach(\App\Models\Affiliate::where('mrr', '>', 0)->get() as $affiliate)
            <li class="list-group-item d-flex justify-content-between">
                <a class="color-600" href="/admin/finance/commissions?byAffiliate={{$affiliate->id}}">
                    {{$affiliate->name}}</a>
            </li>
        @endforeach
    </ul>
</div>

