@extends('layouts.admin', ['title' => $account->name, 'crumbs' => [
    '/admin/accounts' => "Accounts",
    "/admin/accounts/$account->id" => $account->name,
    'Profile'

]])
@section('content')
    <div class="row">
        <div class="col-2">
            @include('admin.accounts.submenu')
        </div>

        <div class="col-lg-10">

            <div class="row">
                <div class="col-lg-8">

                    <div class="card mt-2">
                        <div class="card-body">
                            <form method="post" action="/admin/accounts/{{$account->id}}">
                                @method('PUT')
                                @csrf
                                <h6 class="fw-bold">Company Information</h6>
                                <div class="row g-3 mb-4">

                                    <div class="col-lg-8 col-md-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="name"
                                                   value="{{$account->name}}">
                                            <label>Company Name</label>
                                            <span class="helper-text">Enter the company name</span>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="phone"
                                                   value="{{$account->phone}}">
                                            <label>Primary Contact Phone</label>
                                            <span class="helper-text">Enter the primary contact phone number</span>
                                        </div>
                                    </div>


                                </div>

                                <div class="row g-3 mb-4">

                                    <div class="col-lg-8 col-md-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="address"
                                                   value="{{$account->address}}">
                                            <label>Address</label>
                                            <span class="helper-text">Enter the company address</span>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="address2"
                                                   value="{{$account->address2}}">
                                            <label>Address 2</label>
                                            <span class="helper-text">Suite/Unit, etc</span>
                                        </div>
                                    </div>

                                </div>

                                <div class="row g-3 mb-4">

                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="city"
                                                   value="{{$account->city}}">
                                            <label>City</label>
                                            <span class="helper-text">Enter the company city</span>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="state"
                                                   value="{{$account->state}}">
                                            <label>State</label>
                                            <span class="helper-text">State</span>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="postcode"
                                                   value="{{$account->postcode}}">
                                            <label>Zip</label>
                                            <span class="helper-text">Zip Code</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-lg-4 col-md-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="website"
                                                   value="{{$account->website}}">
                                            <label>Website</label>
                                            <span class="helper-text">Enter Customer Website Address</span>
                                        </div>
                                    </div>


                                    <div class="col-lg-4 col-md-12">
                                        <div class="form-floating">
                                            {!! Form::select('agent_id', \App\Models\User::getAgentsSelectable(), $account->agent_id, ['class' => 'form-control']) !!}
                                            <label>Select Account/Sales Rep</label>
                                            <span class="helper-text">Select the user who sold or is supporting this account.</span>
                                        </div>
                                    </div>


                                    <div class="col-lg-4 col-md-12">
                                        <div class="form-floating">
                                            {!! Form::select('is_commissionable', [0 => 'No', 1 => 'Yes'], $account->is_commissionable, ['class' => 'form-control']) !!}
                                            <label>Account Commissionable?</label>
                                            <span class="helper-text">Select if commissions should be paid on this account</span>
                                        </div>
                                    </div>


                                </div>


                                <div class="row g-3 mb-4">
                                    <input type="submit" class="btn btn-{{bm()}}primary wait" value="Save">
                                </div>


                            </form>
                        </div>
                        <div class="row">
                            {!! moduleHook('admin.accounts.profile.index', ['account' => $account]) !!}
                        </div>
                    </div>


                </div>

                <div class="col-lg-4">

                    @if(hasIntegration(\App\Enums\Core\IntegrationType::Merchant))
                        @include('admin.accounts.profile.method')
                    @endif

                    <div class="card mt-5">
                        <div class="card-body">
                            <p>
                                If you would like for invoices and other billing emails to go to a specific email
                                address, you can
                                add it below, otherwise it will use the primary user on the account.
                            </p>
                            <form method="POST" action="/admin/accounts/{{$account->id}}">
                                @method('PUT')
                                @csrf
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="billing_email"
                                                   value="{{$account->billing_email}}">
                                            <label>Billing E-mail</label>
                                            <span
                                                class="helper-text">Leave blank to use admin user for accounting.</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mt-2">
                                        <input type="submit" name="save" value="Update Billing Email"
                                               class="btn btn-light-primary wait">
                                    </div>
                                </div>
                            </form>
                        </div>


                        @if($account->is_partner)
                            <div class="card border-warning">
                                <div class="card-body">
                                    <p>
                                        {{$account->name}} is currently not enabled as a partner and cannot register
                                        leads or have
                                        commissions tracked.
                                        If you wish to enable this account as a partner, click the button below.
                                    </p>
                                    <a href="/admin/accounts/{{$account->id}}/partner/enable"
                                       class="btn btn-{{bm()}}warning confirm"
                                       data-method="GET"
                                       data-message="Are you sure you want to enable this account as a partner? You will need to set their commission structure after it is enabled.">
                                        <i class="fa fa-user-circle"></i> Enable Partner Controls
                                    </a>
                                </div>
                            </div>
                        @endif


                    </div>


                    <div class="card mt-3">
                        <div class="card-body">
                            <p class="card-title">Update Company Logo</p>
                            <form method="POST" action="/admin/accounts/{{$account->id}}/logo"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <input type="file" name="logo" class="drop"
                                       data-default-file="{{$account->logo_id ? _file($account->logo_id)->relative : null}}"/>
                                <input type="submit" name="submit" class="btn btn-sm btn-{{bm()}}primary mt-3"
                                       value="Update Logo">
                            </form>

                        </div>
                    </div>


                        <a class="btn btn-{{bm()}}danger live mt-4 w-100" data-title="Cancel {{$account->name}}"
                           href="/admin/accounts/{{$account->id}}/cancel"><i class="fa fa-archive"></i> Cancel/Close Account</a>

                </div>

            </div>


        </div>
    </div>
@endsection
