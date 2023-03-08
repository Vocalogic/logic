<div class="card">
    <div class="card-body">
        <a class="live btn btn-sm btn-primary" data-title="Create Task in {{$category->name}}"
           href="/admin/projects/{{$project->id}}/tasks/create?category={{$category->id}}">
            <i class="fa fa-plus"></i> New Task
        </a>
        <table class="table table-striped mt-3">
            <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Est. Hours</th>
                <th>Hours Logged</th>
                <th>Assigned</th>
                <th>Unbilled Amount</th>
            </tr>
            </thead>
            <tbody>
            @foreach($category->tasks as $task)
                <tr class="{{$task->completed ? "table-success" : null}}">
                    <td>
                        <a class="link-info" href="/admin/projects/{{$project->id}}/tasks/{{$task->id}}">#{{$task->id}}</a>
                    </td>
                    <td>{{$task->name}}</td>
                    <td>{{$task->est_hours_min ?: "Not Set"}} - {{$task->est_hours_max ?: "Not Set"}}</td>
                    <td>{{$task->time}}</td>
                    <td>{{$task->assigned ? $task->assigned->short : "Unassigned"}}</td>
                    <td>${{moneyFormat($task->unbilledTime)}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
