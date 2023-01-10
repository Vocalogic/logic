@extends('layouts.admin', ['title' => "Integrations", 'crumbs' => [
     "Integrations",
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Logic Integrations</h1>
            <small class="text-muted">Configure and enable addons for billing, support and more.</small>
        </div>

    </div> <!-- .row end -->

@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12 mt-2">

            @if(env('DEMO_MODE') == true)
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-primary">Integrations are disabled in Demo Mode.</div>
                    </div>
                </div>
            @else

                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Integration Manager</h6>
                    </div>
                    <div class="card-body d-flex align-items-start">
                        <ul class="nav nav-pills custom-horizontal me-2" role="tablist">
                            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#merchants"
                                                    role="tab">Merchants</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#accounting" role="tab">Accounting</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#support" role="tab">Support/Tickets</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#chat"
                                                    role="tab">Chat</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#calendar" role="tab">Calendars</a>
                            </li>
                        </ul>

                        <div class="tab-content ps-3">
                            <div class="tab-pane fade show active" id="merchants" role="tabpanel">
                                <p>
                                    @include('admin.integrations.list', ['type' => \App\Enums\Core\IntegrationType::Merchant, 'limit' => true])

                                </p>
                            </div>

                            <div class="tab-pane fade" id="accounting" role="tabpanel">
                                <p>
                                    @include('admin.integrations.list', ['type' => \App\Enums\Core\IntegrationType::Finance])
                                </p>
                            </div>

                            <div class="tab-pane fade" id="support" role="tabpanel">
                                <p>
                                    @include('admin.integrations.list', ['type' => \App\Enums\Core\IntegrationType::Support])
                                </p>
                            </div>

                            <div class="tab-pane fade" id="chat" role="tabpanel">
                                @include('admin.integrations.list', ['type' => \App\Enums\Core\IntegrationType::Chat])
                            </div>

                            <div class="tab-pane fade" id="calendar" role="tabpanel">
                                @include('admin.integrations.list', ['type' => \App\Enums\Core\IntegrationType::Calendar])
                            </div>
                        </div>
                    </div>
                </div>
    @endif
@endsection
