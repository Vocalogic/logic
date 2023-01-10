@extends('layouts.shop.main', ['title' => $lead->company])


@section('content')
    <section class="user-dashboard-section section-b-space">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-xxl-3 col-lg-4">
                    @include('shop.sales.menu')
                </div>
                <div class="col-xxl-9 col-lg-8">

                    <ul class="nav nav-tabs custom-nav" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="settings-tab" data-bs-toggle="tab"
                                    data-bs-target="#settings" type="button" role="tab"
                                    aria-controls="settings" aria-selected="true">Settings
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="quotes-tab" data-bs-toggle="tab"
                                    data-bs-target="#quotes" type="button" role="tab" aria-controls="info"
                                    aria-selected="false">Quotes
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="discovery-tab" data-bs-toggle="tab"
                                    data-bs-target="#discovery" type="button" role="tab"
                                    aria-controls="discovery" aria-selected="true">Discovery
                            </button>
                        </li>


                    </ul>


                    <div class="tab-content custom-tab" id="myTabContent">

                        <div class="tab-pane fade mt-5" id="settings" role="tabpanel"
                             aria-labelledby="settings-tab">
                            @include('shop.sales.leads.edit_fields')
                        </div>

                        <div class="tab-pane fade show active mt-5" id="quotes" role="tabpanel"
                             aria-labelledby="quotes-tab">
                            @include('shop.sales.leads.quote_list')
                        </div>

                        <div class="tab-pane fade mt-5" id="discovery" role="tabpanel"
                             aria-labelledby="discovery-tab">
                            @include('shop.sales.leads.discovery')
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </section>

@endsection
