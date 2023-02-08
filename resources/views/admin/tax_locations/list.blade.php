<div class="card">
    <div class="card-body">
        <table class="table datatable table-sm table-striped">
            <thead>
            <tr>
                <th>Location</th>
                <th>Sales Tax Rate</th>
                <th>Unpaid</th>
            </tr>
            </thead>
            <tbody>
            @foreach(\App\Models\TaxLocation::orderBy('location')->get() as $location)
                <tr>
                    <td>
                        <a class="live" data-position="right"
                           data-title="Edit {{$location->location}}"
                           href="/admin/tax_locations/{{$location->id}}">
                            {{$location->location}}
                        </a>
                    </td>
                    <td>{{number_format($location->rate,2)}}%</td>
                    <td><a href="/admin/tax_locations/{{$location->id}}/tax_collections">
                            {{$location->collected()->whereNull('tax_batch_id')->count()}}
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
