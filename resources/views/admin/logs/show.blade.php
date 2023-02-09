<table class="table table-striped table-sm small">
    <thead>
        <tr>
            <th>Date</th>
            <th>Message</th>
        </tr>
    </thead>
    <tbody>
        @foreach($logs as $log)
            <tr>
                <td>{{ $log->created_at }}</td>
                <td>{{ $log->log }}</td>
            </tr>
        @endforeach
    </tbody>
</table>