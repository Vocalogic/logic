<table class="table table-striped">
    <thead>
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Status</th>
        <th>Activated On</th>
    </tr>
    </thead>
    <tbody>
    @foreach($account->quotes()->whereNotNull('activated_on')->get() as $quote)
        <tr>
            <td><a href="/admin/quotes/{{$quote->id}}"><span
                        class="badge bg-primary">#{{$quote->id}}</span></a></td>
            <td>{{$quote->name}}</td>
            <td>{{$quote->status}}</td>
            <td>{{$quote->activated_on->format("m/d/y")}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
