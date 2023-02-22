@extends('layouts.admin', ['title' => "Integrations", 'crumbs' => [
     "Integrations",
]])

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
                    <div class="card-body">


                        <div class="row">
                            <div class="col-md-2">
                                <div class="nav flex-column nav-pills text-center" id="v-pills-tab" role="tablist">
                                    <a class="nav-link mb-2 active" id="merchants-tab"
                                       data-bs-toggle="pill" href="#merchants" role="tab"
                                       aria-controls="merchants-home" aria-selected="true">Merchants</a>
                                    <a class="nav-link mb-2" id="accounting-tab"
                                       data-bs-toggle="pill" href="#accounting" role="tab"
                                       aria-controls="accounting-home" aria-selected="true">Accounting</a>
                                    <a class="nav-link mb-2" id="support-tab"
                                       data-bs-toggle="pill" href="#support" role="tab"
                                       aria-controls="support-home" aria-selected="true">Support</a>
                                    <a class="nav-link mb-2" id="chat-tab"
                                       data-bs-toggle="pill" href="#chat" role="tab"
                                       aria-controls="chat-home" aria-selected="true">Chat</a>
                                    <a class="nav-link mb-2" id="calendar-tab"
                                       data-bs-toggle="pill" href="#calendar" role="tab"
                                       aria-controls="calendar-home" aria-selected="true">Calendars</a>
                                    <a class="nav-link mb-2" id="backups-tab"
                                       data-bs-toggle="pill" href="#backup" role="tab"
                                       aria-controls="backup-home" aria-selected="true">Backups</a>
                                </div>
                            </div>
                            <div class="col-md-10">
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

                                    <div class="tab-pane fade" id="backup" role="tabpanel">
                                        @include('admin.integrations.list', ['type' => \App\Enums\Core\IntegrationType::Backup])
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    @endif
@endsection
