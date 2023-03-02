<div class="card">
    <div class="card-body">
        <a class="live btn btn-primary" data-title="Create Task in {{$category->name}}"
           href="/admin/projects/{{$project->id}}/tasks/create?category={{$category->id}}">
            <i class="fa fa-plus"></i> New Task
        </a>
        <table class="table table-striped mt-3">
            <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Est. Hours</th>
                <th>Assigned</th>
                <th>Status</th>
                <th>Method</th>
            </tr>
            </thead>
            <tbody>
            @foreach($category->tasks as $task)
                <tr>
                    <td>
                        <a class="link-info" href="/admin/projects/{{$project->id}}/tasks/{{$task->id}}">#{{$task->id}}</a>
                    </td>
                    <td>{{$task->name}}</td>
                    <td>{{$task->est_hours ?: "Not Set"}}</td>
                    <td>{{$task->assigned ? $task->assigned->name : "Unassigned"}}</td>
                    <td>{{$task->status->value}}</td>
                    <td>{{$task->bill_method}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
