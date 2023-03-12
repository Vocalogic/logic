<h5 class="mt-3">Category Time Budget</h5>
<table class="table table-sm table-striped mt-2">
    <tbody>
    @foreach($category->tasks as $task)
        <tr>
            <td>{{$task->name}}
            <br/>
                <div class="progress animated-progress mb-2">
                    <div class="progress-bar bg-info" role="progressbar" style="width: {{$task->progress}}%"
                         aria-valuenow="{{$task->progress}}" aria-valuemin="0"
                         aria-valuemax="100">
                    </div>
                </div>
            </td>
            <td>{{$task->est_hours_max ?: 0}}</td>
            <td>{{$task->totalWorked}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
