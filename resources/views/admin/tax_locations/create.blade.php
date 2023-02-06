<p>
    Creating a new tax location will apply sales tax to items that are marked as taxable.
</p>
<p>
    <code>NOTE:</code> If you have an integration that supports sales tax overrides (like Quickbooks) then this
    section will not be used.
</p>
<div class="card border-primary">
    <div class="card-body">
        <form method="POST" action="/admin/tax_locations{{$location->id ? "/$location->id" : null}}">
            @csrf
            @method($location->id ? "PUT" : "POST")
            <x-form-input name="location" label="Enter Location/State" value="{{$location->location}}"
                          icon="map-marker">
                Enter the location to map. This is generally a two character state representation like "CA" or "GA"
            </x-form-input>

            <x-form-input name="rate" label="Enter Sales Tax Rate (in %)" value="{{$location->rate}}"
                          icon="money">
                Enter the sales tax rate that is associated with this location.
            </x-form-input>
            <div class="col-lg-12">
                @if($location->id)
                    <a class="confirm text-danger" data-message="Are you sure you want to remove this tax rate?"
                    data-method="DELETE" href="/admin/tax_locations/{{$location->id}}">
                        <i class="fa fa-times"></i> Remove {{$location->location}}
                    </a>
                @endif
                <button type="submit" class="btn btn-primary pull-right ladda" data-style="zoom-out">
                    <i class="fa fa-save"></i> Save
                </button>
            </div>
        </form>
    </div>
</div>
