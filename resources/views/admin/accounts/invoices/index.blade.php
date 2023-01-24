@extends('layouts.admin', ['title' => $account->name, 'crumbs' => [
    '/admin/accounts' => "Accounts",
    "/admin/accounts/$account->id" => $account->name,
    'Invoices'

]])
@section('content')
    <div class="row">
        <div class="col-lg-2 col-xs-12">
            @include('admin.accounts.submenu')
        </div>

        <div class="col-lg-10 col-xs-12">


            <ul class="nav nav-tabs tab-card border-bottom-0 pt-2 fs-6 justify-content-center justify-content-md-start">
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#inv-draft" role="tab">Draft</a>
                </li>
                <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#inv-outstanding"
                                        role="tab">Outstanding</a>
                </li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#inv-paid" role="tab">Paid</a>
                </li>
            </ul>


            <div class="tab-content mt-2">
                <div class="tab-pane fade" id="inv-draft" role="tabpanel">
                    @include('admin.accounts.invoices.draft')
                </div>

                <div class="tab-pane fade show active" id="inv-outstanding" role="tabpanel">
                    @include('admin.accounts.invoices.outstanding')
                </div>

                <div class="tab-pane fade" id="inv-paid" role="tabpanel">
                    @include('admin.accounts.invoices.paid')
                </div>
            </div>
            <a class="btn btn-{{bm()}}primary" href="#newInvoice" data-bs-toggle="modal"><i
                    class="fa fa-plus"></i> new invoice</a>

        </div>
    </div>

    <x-modal name="newInvoice" title="Create new Invoice">
        <div class="row">
            <div class="col-lg-3">
                <p class="text-center mt-4">
                    <i class="fa fa-money text-primary fa-4x"></i>
                </p>
            </div>
            <div class="col-lg-9">
                <p class="mb-3">
                    Enter the number of days before due for this invoice. The default will be the
                    net terms on the account. Simply click <code>Create Invoice</code> to continue.
                </p>
                <form method="post" action="/admin/accounts/{{$account->id}}/invoices" class="newInvoiceForm">
                    @method('POST')
                    @csrf
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <x-form-input name="terms" label="Due in Days" icon="calendar-o"
                                          labelWidth="6"
                                          value="{{$account->net_terms}}">
                                How many days until this invoice is due?
                            </x-form-input>
                            <x-form-input name="po" label="Purchase Order Number" icon="folder-o"
                                          value="{{$account->po}}">
                                If this invoice should be assigned a PO, enter it here.
                            </x-form-input>
                        </div>

                        <div class="row mt-2">
                            <div class="col-lg-12">
                                <input type="submit" name="submit" value="Create Invoice"
                                       class="btn btn-{{bm()}}primary w-100 wait" data-anchor=".newInvoiceForm">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </x-modal>

@endsection

