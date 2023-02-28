<table class="table table-striped datatable">
    <thead>
    <tr>
        <th>Date</th>
        <th>Message</th>
        <th>Detail</th>
    </tr>
    </thead>
    <tbody>
    @foreach($logs->where('log_level', $level)->all() as $log)
        <tr>
            <td>{{ $log->created_at->format("m/d/y h:ia") }}</td>
            <td>{{ $log->log }}</td>
            <td>{!!  $log->detail !!}</td>
        </tr>
    @endforeach
    </tbody>
</table>

