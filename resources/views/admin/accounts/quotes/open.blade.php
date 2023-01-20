<table class="table table-striped">
    <thead>
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Status</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($account->quotes()->whereNull('activated_on')->get() as $quote)
        <tr>
            <td><a href="/admin/quotes/{{$quote->id}}"><span
                        class="badge bg-{{bm()}}primary">#{{$quote->id}}</span></a></td>
            <td>{{$quote->name}}</td>
            <td>{{$quote->status}}</td>
            <td>${{moneyFormat($quote->total)}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
