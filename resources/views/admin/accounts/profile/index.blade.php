@extends('layouts.admin', ['title' => $account->name, 'crumbs' => [
    '/admin/accounts' => "Accounts",
    "/admin/accounts/$account->id" => $account->name,
    'Profile'

], 'log' => $account->logLink])
@section('content')
    <div class="row">
        <div class="col-lg-2 col-xs-12">
            @include('admin.accounts.submenu')
        </div>

        <div class="col-lg-10 col-xs-12">

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
                                            <input type="text"
                                                   class="address form-control {{setting('account.maps_key') ? 'mapsEnabled': null}}"
                                                   id="addressQuery" name="address"
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
                                            <input type="text" class="city form-control" name="city"
                                                   value="{{$account->city}}">
                                            <label>City</label>
                                            <span class="helper-text">Enter the company city</span>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-12">
                                        <div class="form-floating">
                                            <input type="text" class="state form-control" name="state"
                                                   value="{{$account->state}}">
                                            <label>State</label>
                                            <span class="helper-text">State</span>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-12">
                                        <div class="form-floating">
                                            <input type="text" class="zip form-control" name="postcode"
                                                   value="{{$account->postcode}}">
                                            <label>Zip</label>
                                            <span class="helper-text">Zip Code</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-floating">
                                            {!! Form::select('agent_id', \App\Models\User::getAgentsSelectable(), $account->agent_id, ['class' => 'form-control']) !!}
                                            <label>Select Account/Sales Rep</label>
                                            <span class="helper-text">Select the user who sold or is supporting this account.</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-floating">
                                            {!! Form::select('is_commissionable', [0 => 'No', 1 => 'Yes'], $account->is_commissionable, ['class' => 'form-control']) !!}
                                            <label>Account Commissionable?</label>
                                            <span class="helper-text">Select if commissions should be paid on this account</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-lg-6">
                                        <div class="form-floating">
                                            {!! Form::select('affiliate_id', \App\Models\Affiliate::getSelectable(), $account->affiliate_id, ['class' => 'form-control']) !!}
                                            <label>Select Affiliate</label>
                                            <span class="helper-text">
                                                Select an affiliate agent to be commissioned.
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="website"
                                                   value="{{$account->website}}">
                                            <label>Website</label>
                                            <span class="helper-text">Enter Customer Website Address</span>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-floating">
                                            {!! Form::select('parent_id', $account->getSelectableParents(), $account->parent_id, ['class' => 'form-control']) !!}
                                            <label>Parent Account:</label>
                                            <span class="helper-text">If this account should be associated to another account, you can select it here.</span>
                                        </div>
                                    </div>

                                </div>


                                <div class="row g-3 mb-4">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary ladda pull-right"
                                                data-style="zoom-out">
                                            <i class="fa fa-save"></i> Save Company Profile
                                        </button>
                                    </div>
                                </div>


                            </form>
                        </div>
                        <div class="row">
                            {!! moduleHook('admin.accounts.profile.index', ['account' => $account]) !!}
                        </div>
                    </div>


                </div>

                <div class="col-lg-4">

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


                    <div class="card mt-2">
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
