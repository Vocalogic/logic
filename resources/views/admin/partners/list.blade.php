<div class="card mt-3">
    <div class="card-body">
        <table class="table table-sm table-striped">
            <thead>
            <tr>
                <th>Partner</th><th>Comm. Out</th><th>Comm. In</th><th>NET Days</th><th>Status</th>
            </tr>
            </thead>
            <tbody>
            @foreach(\App\Models\Partner::where('active', true)->get() as $partner)
            <tr>
                <td><a href="/admin/partners/{{$partner->id}}">{{$partner->name}}</a></td>
                <td>{{$partner->commOut}}</td>
                <td>{{$partner->commIn}}</td>
                <td>{{$partner->net_days}}</td>
                <td>{{$partner->status}}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
