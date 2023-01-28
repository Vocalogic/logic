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

            <a class="btn btn-primary" href="#newQuote" data-bs-toggle="modal">
                <i class="fa fa-plus"></i> new quote</a>

            <x-modal name="newQuote" title="Create Quote for {{$account->name}}">
                <p class="mb-3">
                    Enter the name of the new quote. This quote will be created as a draft and you will be
                    prompted to enter services once saved.
                </p>
                <div class="card border-primary">
                    <div class="card-body">
                        <form method="post" action="/admin/accounts/{{$account->id}}/quotes" class="quoteForm">
                            @method('POST')
                            @csrf
                            @props(['val' => $account->name . " Quote ". now()->format("m/d")])
                            <x-form-input name="name" :value="$val"
                                          label="Quote Name"
                                          placeholder="Sample Quote Name"
                                          icon="sliders">
                                Enter the name for the quote.
                            </x-form-input>
                            <div class="row mt-2">
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-primary ladda pull-right"
                                            data-style="zoom-out">
                                        <i class="fa fa-plus"></i> Create Quote
                                    </button>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </x-modal>
        </div>
    </div>
@endsection


