<div class="card">
    <div class="card-body">

        <form method="post" action="{{$lead->id ? "/admin/leads/$lead->id" : "/admin/leads"}}">
            @method($lead->id ? 'PUT' : 'POST')
            @csrf
            <h6 class="fw-bold">Contact Information</h6>
            <div class="row g-3 mb-4">

                <div class="col-lg-4 col-md-12">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="company" value="{{$lead->company}}">
                        <label>Company Name</label>
                        <span class="helper-text">Enter the company name</span>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="contact" value="{{$lead->contact}}">
                        <label>Contact Name</label>
                        <span class="helper-text">Enter the contact's full name</span>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="email" value="{{$lead->email}}">
                        <label>Primary Email</label>
                        <span class="helper-text">Enter the contact's email address</span>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">

                <div class="col-lg-8 col-md-12">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="address" value="{{$lead->address}}">
                        <label>Address</label>
                        <span class="helper-text">Enter the company address</span>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="address2" value="{{$lead->address2}}">
                        <label>Address 2</label>
                        <span class="helper-text">Suite/Unit, etc</span>
                    </div>
                </div>

            </div>

            <div class="row g-3 mb-4">

                <div class="col-lg-6 col-md-12">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="city" value="{{$lead->city}}">
                        <label>City</label>
                        <span class="helper-text">Enter the company city</span>
                    </div>
                </div>

                <div class="col-lg-3 col-md-12">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="state" value="{{$lead->state}}">
                        <label>State</label>
                        <span class="helper-text">State</span>
                    </div>
                </div>

                <div class="col-lg-3 col-md-12">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="zip" value="{{$lead->zip}}">
                        <label>Zip</label>
                        <span class="helper-text">Zip Code</span>
                    </div>
                </div>


            </div>

            <div class="row g-3 mb-4">
                <div class="col-lg-4 col-md-12">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="phone" value="{{$lead->phone}}">
                        <label>Contact Phone</label>
                        <span class="helper-text">Enter the primary contact phone number</span>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="form-floating mb-2">
                        {!! Form::select('lead_origin_id', array_replace([0 => '-- Select Origin --'], \App\Models\LeadOrigin::all()->pluck('name', 'id')->all()), $lead->lead_origin_id, ['class' => 'form-select', 'aria-label' => 'Select Origin']) !!}
                        <label>Select Lead Origin</label>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12">
                    <div class="form-floating mb-2">
                        <textarea class="form-control"
                                  name="lead_origin_detail">{!! $lead->lead_origin_detail !!}</textarea>
                        <label>Lead Origin Note (opt)</label>
                        <span class="helper-text">Details on where this lead originated.</span>
                    </div>
                </div>

            </div>
            <div class="row g-3 mb-4">
                <input type="submit" class="btn btn-{{bm()}}primary wait w-25 updateDetails" value="Save">
            </div>


        </form>
    </div>
</div>

<div class="card mt-3">
    <div class="card-body">
        <p class="card-title">Forecasted Date</p>
        <p class="card-text">When you have a preferred quote and a target date for this account to convert with
            high confidence,
            you can enter the date of when you want to forecast this MRR revenue.
        </p>


        @if(!$lead->hasPreferred)
            <div role="alert" class="alert border-warning"><strong>WARNING: </strong>This lead does not have a
                preferred quote, so its value cannot be
                accurately forecasted.
            </div>
        @else

            <form method="post" action="{{$lead->id ? "/admin/leads/$lead->id" : "/admin/leads"}}">
                @method($lead->id ? 'PUT' : 'POST')
                @csrf
                <div class="row g-3 mb-4">
                    <div class="col-lg-6 col-md-12">
                        <input type="date" class="form-control" name="forecast_date"
                               placeholder="Select Forecasted Date"
                               value="{{$lead->forecast_date?->format("Y-m-d")}}">
                        <span
                            class="helper-text">Enter the date you feel confident this lead will convert.</span>
                    </div>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-lg-12">
                        <textarea class="form-control" name="forecast_note">{{$lead->forecast_note}}</textarea>
                        <span  class="helper-text">Enter a note about this forecast.
                                    (ex. What is the client waiting on?)</span>
                    </div>
                </div>
                <input type="submit" class="btn btn-{{bm()}}primary mt-2" value="Update Forecast Information">
            </form>

        @endif


    </div>
</div>
