@extends('layouts.admin', ['title' => 'Coupon Management', 'crumbs' => [
     "Coupon Management",
]])

@section('content')
    <div class="row">
        <div class="col-xs-12 col-md-2">
            <a href="/admin/coupons/create" class="btn btn-primary w-100"><i class="fa fa-plus"></i> Create Coupon</a>

        </div>
        <div class="col-xs-12 col-md-10">
            <div class="card">
                <div class="card-body">
                    <table class="table datatable">
                        <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Affiliate</th>
                            <th>Active On</th>
                            <th>Remaining</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(\App\Models\Coupon::orderBy('coupon')->get() as $coupon)
                            <tr>
                                <td><a href="/admin/coupons/{{$coupon->id}}">{{$coupon->coupon}}</a></td>
                                <td>{{$coupon->name}}</td>
                                <td>{{$coupon->affiliate ? $coupon->affiliate->name : "None"}}</td>
                                <td>{{$coupon->start ? $coupon->start->format("m/d/y h:ia") : "Not Started"}} - {{$coupon->end ? $coupon->end->format("m/d/y h:ia") : "No End Date"}}</td>
                                <td>{{$coupon->remaining > -1 ? $coupon->remaining : "Unlimited"}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
