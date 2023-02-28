@extends('layouts.admin', ['title' => 'Transaction Review', 'crumbs' => ['Transactions']])


@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Transaction Review</h1>
            <small class="text-muted">Review your current transactions</small>
        </div>

    </div> <!-- .row end -->

@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            @livewire('iterators.transaction-iterator-component')
        </div>
    </div>

@endsection
