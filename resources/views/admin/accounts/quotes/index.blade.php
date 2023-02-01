@extends('layouts.admin', ['title' => $account->name, 'crumbs' => [
    '/admin/accounts' => "Accounts",
    "/admin/accounts/$account->id" => $account->name,
    'Quotes'

]])
@section('content')
    <div class="row">
        <div class="col-lg-2 col-xs-12">
            @include('admin.accounts.submenu')
        </div>

        <div class="col-lg-10 col-xs-12">

            <ul class="nav nav-tabs tab-card border-bottom-0 pt-2 fs-6 justify-content-center justify-content-md-start">
                <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#quote-open"
                                        role="tab">Open</a>
                </li>
                <li class="nav-item"><a class="nav-link " data-bs-toggle="tab" href="#quote-sold"
                                        role="tab">Sold</a>
                </li>

            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="quote-open" role="tabpanel">
                    @include('admin.accounts.quotes.open')
                </div>

                <div class="tab-pane fade" id="quote-sold" role="tabpanel">
                    @include('admin.accounts.quotes.sold')
                </div>
            </div>

            <a class="btn btn-primary live" data-title="Create Quote for {{$account->name}}"
               href="/admin/quotes/create?account_id={{$account->id}}">
                <i class="fa fa-plus"></i> new quote
            </a>


        </div>
    </div>
@endsection


