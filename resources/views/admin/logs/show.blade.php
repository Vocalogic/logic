<table class="table table-striped table-sm small">
    <thead>
    <tr>
        <th>Date</th>
        <th>Message</th>
        <th>Detail</th>
    </tr>
    </thead>
    <tbody>
    @foreach($logs as $log)
        <tr>
            <td>{{ $log->created_at->format("m/d/y h:ia") }}</td>
            <td>{{ $log->log }}</td>
            <td>{!!  $log->detail !!}</td>
        </tr>
    @endforeach
    </tbody>
</table>
