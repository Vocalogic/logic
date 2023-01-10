@extends('layouts.admin', ['title' => "Commission Report", 'crumbs' => [
     "Commissions",
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Outstanding Commission Report</h1>
            <small class="text-muted">Show commissions and their status</small>
        </div>
    </div> <!-- .row end -->

@endsection

@section('content')
    <div class="row">
        <div class="col-lg-2 mt-2">
            <a href="/admin/finance/commission_batches/create" data-title="Create new Commission Batch"
               class="btn btn-block btn-{{bm()}}primary w-100 live mb-3">
                <i class="fa fa-plus"></i> Create new Batch
            </a>
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">By Status</h6>
                    <ul class="list-group list-group-custom">
                        @foreach(\App\Enums\Core\CommissionStatus::cases() as $status)
                            <li class="list-group-item">
                                <a class="color-600" href="/admin/finance/commissions?status={{$status->value}}">
                                    {{$status->getHuman()}} ({{$status->count()}})
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title">By Agent</h6>
                    <ul class="list-group list-group-custom">
                        @foreach(\App\Models\User::where('agent_comm_mrc', '>', 0)->get() as $user)
                            <li class="list-group-item">
                                <a class="color-600" href="/admin/finance/commissions?byUser={{$user->id}}">
                                    {{$user->name}}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>


        <div class="col-lg-10 mt-2">
            <div class="card">
                <div class="card-body">
                    @include('admin.finance.commissions.list')
                </div>
            </div>
        </div>
    </div>
@endsection
