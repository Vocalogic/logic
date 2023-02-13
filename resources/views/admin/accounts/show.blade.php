@extends('layouts.admin', ['title' => $account->name, 'crumbs' => [
    '/admin/accounts' => "Accounts",
    $account->name

]])
@section('content')
    <div class="row">

        <div class="col-lg-2 col-xs-12">
            @include('admin.accounts.submenu')
        </div>

        <div class="col-lg-10 col-xs-12">
            @if($account->services_changed)
                <div class="alert {{bma()}}info">
                    A change has been made to this account and the customer has not been informed of the change. If you
                    would like to send an automated notification you can
                    <a href="/admin/accounts/{{$account->id}}/notifyServices">click here</a> to send a notification.
                    If you do not wish to send a notification you can
                    <a href="/admin/accounts/{{$account->id}}/clearServices">click here</a> to clear this notification.
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">{{$account->name}}
                        @if($account->partner)
                            <span class="badge bg-{{bm()}}info">Partner: {{$account->partner->name}}</span>
                        @endif
                    </h6>

                    <ul class="nav nav-tabs tab-page-toolbar rounded d-inline-flex mt-2 mb-2" role="tablist">

                        <li class="nav-item"><a class="nav-link {{$tab->overview ? "active" : null}}"
                                                data-bs-toggle="tab"
                                                href="#overview" role="tab">Overview</a>
                        </li>
                        <li class="nav-item"><a class="nav-link {{$tab->services ? "active" : null}}"
                                                data-bs-toggle="tab"
                                                href="#services" role="tab">Services</a>
                        </li>
                        <li class="nav-item"><a class="nav-link"
                                                {{$tab->invoices ? "active" : null}} data-bs-toggle="tab"
                                                href="#invoices" role="tab">Invoices</a>
                        </li>

                        <li class="nav-item"><a class="nav-link {{$tab->users ? "active" : null}}"
                                                data-bs-toggle="tab"
                                                href="#users" role="tab">Users</a>
                        </li>

                        <li class="nav-item"><a class="nav-link"
                                                {{$tab->quotes ? "active" : null}} data-bs-toggle="tab"
                                                href="#quotes" role="tab">Quotes</a>
                        </li>


                        <li class="nav-item"><a class="nav-link" {{$tab->events ? "active" : null}} data-bs-toggle="tab"
                                                href="#events" role="tab">Events</a>
                        </li>

                        <li class="nav-item"><a class="nav-link"
                                                {{$tab->profile ? "active" : null}} data-bs-toggle="tab"
                                                href="#profile" role="tab">Profile</a>
                        </li>
                        <li class="nav-item"><a class="nav-link {{$tab->pricing ? "active" : null}} "
                                                data-bs-toggle="tab"
                                                href="#pricing" role="tab">Pricing</a>
                        </li>
                        <li class="nav-item"><a class="nav-link {{$tab->files ? "active" : null}}" data-bs-toggle="tab"
                                                href="#files" role="tab">Files</a>
                        </li>
                        @if($account->is_partner)
                            <li class="nav-item"><a class="nav-link {{$tab->partner ? "active" : null}}"
                                                    data-bs-toggle="tab"
                                                    href="#partner" role="tab">Partner</a>
                            </li>
                        @endif
                        {!! moduleHook('admin.accounts.account_tabs', ['account' => $account, 'tab' => $tab]) !!}

                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade {{$tab->overview ? " show active" : null}}" id="overview"
                             role="tabpanel">
                            @include('admin.accounts.overview.index')
                        </div>

                        <div class="tab-pane fade {{$tab->services ? "show active" : null}}" id="services"
                             role="tabpanel">
                            @include('admin.accounts.services.index')
                        </div>

                        <div class="tab-pane fade {{$tab->users ? "show active" : null}}" id="users"
                             role="tabpanel">
                            @include('admin.accounts.users.index')
                        </div>

                        <div class="tab-pane fade {{$tab->quotes ? "show active" : null}}" id="quotes"
                             role="tabpanel">
                            @if(isset(app('request')->quote))
                                @include('admin.quotes.show_account', ['quote' => \App\Models\Quote::find(app('request')->quote)])
                            @else
                                @include('admin.accounts.quotes.index')
                            @endif
                        </div>


                        <div class="tab-pane fade {{$tab->profile ? "show active" : null}}" id="profile"
                             role="tabpanel">
                            @include('admin.accounts.profile.index')
                        </div>
                        <div class="tab-pane fade {{$tab->pricing ? "show active" : null}} " id="pricing"
                             role="tabpanel">
                            @include('admin.accounts.pricing.index')
                        </div>

                        <div class="tab-pane fade {{$tab->files ? "show active" : null}}" id="files" role="tabpanel">
                            @include('admin.accounts.files.index')
                        </div>

                        <div class="tab-pane fade {{$tab->events ? "show active" : null}}" id="events" role="tabpanel">
                            @include('admin.accounts.events.index')
                        </div>

                        <div class="tab-pane fade {{$tab->invoices ? "show active" : null}}" id="invoices"
                             role="tabpanel">
                            @if(isset(app('request')->invoice))
                                @include('admin.accounts.invoices.show', ['invoice' => \App\Models\Invoice::find(app('request')->invoice)])
                            @else
                                @include('admin.accounts.invoices.index')
                            @endif
                        </div>


                        @if($account->is_partner)
                            <div class="tab-pane fade {{$tab->partner ? "show active" : null}}" id="partner"
                                 role="tabpanel">
                                @include('admin.accounts.partner.index')
                            </div>
                        @endif

                        {!! moduleHook('admin.accounts.account_tabdata', ['account' => $account, 'tab' => $tab]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')

    @if(getIntegration(\App\Enums\Core\IntegrationType::Merchant) == \App\Enums\Core\IntegrationRegistry::LogicPay)
        <script type="text/javascript" src="/assets/js/logicpay.js"></script>
    @endif

@endsection
