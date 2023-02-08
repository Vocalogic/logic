@extends('layouts.admin', ['title' => "Tax Locations", 'crumbs' => [
     "/admin/tax_locations" => "Tax Locations",
     "Tax Collected in $taxLocation->location"
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Taxes Collected in {{$taxLocation->location}}</h1>
            <small class="text-muted">See how much has been collected from customers for taxes for each
                location.</small>
        </div>

    </div> <!-- .row end -->

@endsection
@section('content')
    <div class="row">
        <div class="col-xs-12 col-lg-2">
            <a class='live btn btn-primary' href="/admin/tax_locations/{{$taxLocation->id}}/tax_collections/create">
                <i class="fa fa-plus"></i> Create Tax Payment
            </a>
        </div>

        <div class="col-xs-12 col-lg-10">
            <div class="card">
                <div class="card-body">
                    <table class="table datatable">
                        <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Account</th>
                            <th>Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($taxLocation->collected()->whereNull('tax_batch_id')->get() as $collection)
                            <tr>
                                <td><a href="/admin/invoices/{{$collection->invoice->id}}">#{{$collection->invoice->id}}</a></td>
                                <td><a href="/admin/accounts/{{$collection->invoice->account->id}}">{{$collection->invoice->account->name}}</a></td>
                                <td>${{moneyFormat($collection->amount)}}</td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>
            </div>

        </div>

    </div>

@endsection
