@extends('layouts.admin', ['title' => 'Transaction Review'])


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
            <div class="card">
                <div class="card-body">
                    <table class="table table-sm table-striped datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Invoice</th>
                                <th>Account</th>
                                <th>Amount</th>
                                <th>Fee</th>
                                <th>Net</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\Transaction::all() as $transaction)
                                <tr>
                                    <td>{{$transaction->id}}</td>
                                    <td>{{$transaction->created_at->format("Y-m-d")}}</td>
                                    <td><a href="/admin/accounts/{{$transaction->account->id}}/invoices/{{$transaction->invoice->id}}">#{{$transaction->invoice->id}}</a></td>
                                    <td><a href="/admin/accounts/{{$transaction->account->id}}">{{$transaction->account->name}}</a></td>
                                    <td>${{moneyFormat($transaction->amount)}}
                                    @if($transaction->remote_transaction_id)<br/><small class="text-muted">{{$transaction->remote_transaction_id}}</small>@endif</td>
                                    <td>${{moneyFormat($transaction->fee)}}</td>
                                    <td>${{moneyFormat($transaction->net)}}</td>
                                    <td>{{$transaction->method}}</td>

                                </tr>
                                @endforeach
                        </tbody>
                    </table>


                </div>

            </div>
        </div>
    </div>

@endsection
