<div class="card">
    <div class="card-body">
        <table class="table datatable table-sm table-striped">
            <thead>
            <tr>
                <th>Location</th>
                <th>Sales Tax Rate</th>
            </tr>
            </thead>
            <tbody>
            @foreach(\App\Models\TaxLocation::orderBy('location')->get() as $location)
                <tr>
                    <td>
                        <a class="live"
                           data-title="Edit {{$location->location}}"
                           href="/admin/tax_locations/{{$location->id}}">
                            {{$location->location}}
                        </a>
                    </td>
                    <td>{{number_format($location->rate,2)}}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
