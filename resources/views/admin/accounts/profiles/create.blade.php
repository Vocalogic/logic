<p>
    Create/Update your billing profile below. Account services that are assgined to billing profiles
    will not be billed in the same monthly invoice as the default account services.
</p>
<div class="row mt-3">
    <form method="POST" action="/admin/accounts/{{$account->id}}/profiles{{$profile->id ? "/$profile->id" : null}}">
        @method($profile->id ? "PUT" : "POST")
        @csrf
        <x-form-input name="name" icon="bars" label="Recurring Profile Name" value="{{$profile->name}}">
            This name will be shown on invoices when generated
        </x-form-input>

        <x-form-input name="po" label="Purchase Order Number (optional)" icon="info" value="{{$profile->po}}">
            Enter a purchase order number if required
        </x-form-input>
        @props(['next_bill' => $profile->next_bill ? $profile->next_bill->format("Y-m-d") : null])
        <x-form-input type="date" name="next_bill" label="Next Bill Date" icon="calendar-o" :value="$next_bill">
            Select the date when this recurring profile should run next
        </x-form-input>

        <x-form-input name="bills_on" label="Bills on Day" icon="calendar-o" value="{{$profile->bills_on}}">
            What day of the month should this bill?
        </x-form-input>
        @props(['auto' => [0 => 'No', 1 => 'Yes']])
        <x-form-select name="auto_bill" label="Auto-Process via Auto-Bill?" :options="$auto" icon="credit-card" selected="{{$profile->auto_bill}}">
            Should this recurring profile automatically process on due date?
        </x-form-select>

        <div class="row mt-3">
            <div class="col-lg-12">
                @if($profile->id)
                    <a href="/admin/accounts/{{$account->id}}/profiles/{{$profile->id}}"
                       class="confirm text-danger"
                       data-method="DELETE"
                       data-message="Deleting this recurring profile will remove any services assigned to it.">
                        Remove Billing Profile
                    </a>
            @endif
                <button type="submit" class="btn btn-primary ladda pull-right" data-style="expand-left">
                    <i class="fa fa-save"></i> Save Profile
                </button>
            </div>
        </div>

    </form>
</div>
