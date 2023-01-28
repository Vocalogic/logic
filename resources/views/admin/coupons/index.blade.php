@extends('layouts.admin', ['title' => 'Coupon Management', 'crumbs' => [
     "Coupon Management",
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Manage Coupons</h1>
            <small class="text-muted">Manage Coupons that Customers can apply at checkout</small>
        </div>
    </div> <!-- .row end -->

@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-sm mt-2">
                        <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Active On</th>
                            <th>Remaining</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(\App\Models\Coupon::orderBy('coupon')->get() as $coupon)
                            <tr>
                                <td><a href="/admin/coupons/{{$coupon->id}}">{{$coupon->coupon}}</a></td>
                                <td>{{$coupon->name}}</td>
                                <td>{{$coupon->total_invoice ? "Total Invoice Discount" : "Per Product Discount"}} </td>
                                <td>{{$coupon->start ? $coupon->start->format("m/d/y h:ia") : "Not Started"}} - {{$coupon->end ? $coupon->end->format("m/d/y h:ia") : "No End Date"}}</td>
                                <td>{{$coupon->remaining > -1 ? $coupon->remaining : "Unlimited"}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <a href="/admin/coupons/create" class="btn btn-primary mt-3"><i class="fa fa-plus"></i> Create Coupon</a>

        </div>
    </div>
@endsection
