@extends('layouts.admin', ['title' => 'Hardware/Product Vendors'])


@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Hardware/Product Vendors</h1>
            <small class="text-muted">For shipping/hardware orders, create your vendors here.</small>
        </div>

    </div> <!-- .row end -->

@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <td>Vendor</td>
                            <td>Sales Rep/Contact</td>
                            <td>Sales Rep Email</td>
                            <td>Sales Rep Phone</td>
                            <td>New Order Email</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(\App\Models\Vendor::all() as $vendor)
                            <tr>
                                <td><a class="live" data-title="Edit {{$vendor->name}}" href="/admin/vendors/{{$vendor->id}}">{{$vendor->name}}</a></td>
                                <td>{{$vendor->rep_name}}</td>
                                <td>{{$vendor->rep_email}}</td>
                                <td>{{$vendor->rep_phone}}</td>
                                <td>{{$vendor->order_email}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <a class="btn btn-primary mt-3" href="#newVendor" data-bs-toggle="modal"><i class="fa fa-plus"></i> Add Vendor</a>

                </div>

            </div>
        </div>
    </div>




    <div class="modal fade" id="newVendor" tabindex="-1" role="dialog" aria-labelledby="liveModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create new Vendor</h5>
                </div>
                <div class="modal-body">
                    <p class="mb-3">
                        Hardware Vendors are vendors you use to order hardware. You can create yourself as a vendor
                        if necessary to handle certain orders internally.
                    </p>
                    <form method="post" action="/admin/vendors">
                        @method('POST')
                        @csrf
                        <div class="row g-3 mb-3">
                            <div class="col-lg-6 col-md-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="name" value="">
                                    <label>Vendor Name</label>
                                    <span class="helper-text">Enter the vendor company name</span>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="rep_name" value="">
                                    <label>Sales Rep/Contact</label>
                                    <span class="helper-text">Enter your primary contact's name</span>
                                </div>
                            </div>
                        </div>
                        <div class="row  mb-3">
                            <div class="col-lg-6 col-md-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="rep_email" value="">
                                    <label>Sales Rep Email</label>
                                    <span class="helper-text">Enter the sales rep's email.</span>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="rep_phone" value="">
                                    <label>Sales Rep Phone Number</label>
                                    <span class="helper-text">Enter the sales rep's phone number.</span>
                                </div>
                            </div>

                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6 col-md-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="order_email" value="">
                                    <label>E-mail Address (new orders)</label>
                                    <span class="helper-text">E-mail address for sending hardware orders.</span>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 mt-3">
                                <button type="submit" class="btn btn-primary ladda pull-right" data-style="zoom-out">
                                    <i class="fa fa-save"></i> Save Vendor
                                </button>
                            </div>


                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>



@endsection
