<div class="row mt-3">
    <div class="col-lg-12">


        <h5>Recurring Billing Profiles</h5>
        <p>
            In some cases, your customer may request that items be billed on separate dates or
            simply in separate invoices. You can create a billing profile here, and assign account
            services to be billed on a separate profile here.
        </p>
    </div>

    <div class="col-lg-12 mt-2">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Bills On</th>
                </tr>
            </thead>
            <tbody>
            @foreach($account->recurringProfiles as $profile)
                <tr>
                    <td><a class="live" data-title="{{$profile->name}}" href="/admin/accounts/{{$account->id}}/profiles/{{$profile->id}}">{{$profile->name}}</a>
                    @if($profile->auto_bill)
                        <span class="badge badge-outline-success">autobill</span>
                        @endif
                    </td>
                    <td>{{$profile->next_bill ? $profile->next_bill->format("m/d/y") : "Not Set"}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    <div class="col-lg-12 mt-3">
        <a class="live btn btn-sm btn-primary" data-title="Create new Recurring Profile" href="/admin/accounts/{{$account->id}}/profiles/create">
            <i class="fa fa-plus"></i> New Recurring Profile
        </a>

    </div>


</div>
