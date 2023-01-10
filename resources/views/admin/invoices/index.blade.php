@extends('layouts.admin', ['title' => 'Invoice Management'])


@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Invoice Management</h1>
            <small class="text-muted">Invoice overview and status</small>
        </div>

    </div> <!-- .row end -->

@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">

                    <ul class="nav nav-tabs tab-card border-bottom-0 pt-2 fs-6 justify-content-center justify-content-md-start"
                        role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#outstanding" role="tab">
                                <i class="fa fa-outdent"></i><span
                                    class="d-none d-sm-inline-block ms-2">Outstanding ({{\App\Models\Invoice::getCountByType(\App\Enums\Core\InvoiceStatus::SENT)}})</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#pastdue" role="tab">
                                <i class="fa fa-exclamation-circle"></i><span class="d-none d-sm-inline-block ms-2">Past Due ({{\App\Models\Invoice::getCountPastDue()}})</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#draft" role="tab">
                                <i class="fa fa-archive"></i><span class="d-none d-sm-inline-block ms-2">Draft ({{\App\Models\Invoice::getCountByType(\App\Enums\Core\InvoiceStatus::DRAFT)}})</span>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#paid" role="tab">
                                <i class="fa fa-check"></i><span class="d-none d-sm-inline-block ms-2">Paid ({{\App\Models\Invoice::getCountByType(\App\Enums\Core\InvoiceStatus::PAID)}})</span>
                            </a>
                        </li>

                    </ul>


                    <div class="tab-content mt-3">
                        <div class="tab-pane active" id="outstanding" role="tabpanel">
                            @include('admin.invoices.outstanding')
                        </div>
                        <div class="tab-pane fade" id="pastdue" role="tabpanel">
                            @include('admin.invoices.pastdue')
                        </div>

                        <div class="tab-pane fade" id="draft" role="tabpanel">
                            @include('admin.invoices.draft')

                        </div>

                        <div class="tab-pane fade" id="paid" role="tabpanel">
                            @include('admin.invoices.paid')
                        </div>
                    </div>


                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="lchart" id="invoiceStatus"
                     data-title="Invoices by Status"
                     data-height="300"
                     data-url="/admin/graph/INVOICED?fn=getInvoiceStatusPie&seriesType=donut"
                     data-type="donut"
                     data-disable-toolbar="true"
                     data-wait="Getting Invoice Status...">
                </div>
            </div>


            <div class="card mt-3">
                <div class="lchart" id="totalQuoteValue"
                     data-title="Invoice Outstanding Amount"
                     data-height="300"
                     data-url="/admin/graph/TOTAL_OUTSTANDING?days=6"
                     data-xtype="datetime"
                     data-type="line"
                     data-disable-toolbar="true"
                     data-wait="Getting Invoice Outstanding...">
                </div>
            </div>

        </div>

    </div>

@endsection
