<a class="live btn btn-primary btn-sm"
   href="/admin/projects/{{$project->id}}/tasks/{{$task->id}}/entries/create"
   data-title="Add Time Entry: [{{$task->name}}]">
    <i class="fa fa-plus"></i> New Time Entry
</a>
<table class="table mt-3 table-striped">
    <thead class="table-light">
    <tr>
        <th>#</th>
        <th>Entered</th>
        <th>Work Completed</th>
        <th>Hours</th>
        <th>By</th>
        <th>Invoice</th>
    </tr>
    </thead>
    <tbody>
        @foreach($task->entries as $entry)
            <tr>
                <td><a class="live link-info" data-title="Edit Entry #{{$entry->id}}"
                    href="/admin/projects/{{$project->id}}/tasks/{{$task->id}}/entries/{{$entry->id}}">
                        <span class="badge badge-outline-{{$entry->billable ? "success" : "warning"}}">#{{$entry->id}}</span>
                    </a>
                </td>
                <td>{{$entry->created_at->format("m/d/y h:ia")}}</td>
                <td>{!! nl2br($entry->description) !!}</td>
                <td>{{$entry->hours}}</td>
                <td>{{$entry->user->short}}</td>
                <td>{!! $entry->invoice ? "<a class='text-info' href='/admin/invoices/{$entry->invoice->id}'>#{$entry->invoice->id}" : "Unbilled"!!}</td>
            </tr>
        @endforeach
    </tbody>
</table>
