<p>
    Time entries are used to log work completed on a task and bill hours respectively. You cannot edit
    a time entry after it has been assigned to an invoice.
</p>
<form method="POST" action="/admin/projects/{{$project->id}}/tasks/{{$task->id}}/entries{{$entry->id ? "/$entry->id" : null}}">
    @method($entry->id ? "PUT" : "POST")
    @csrf
    <x-form-input name="description" type="textarea" icon="bars" placeholder="Enter details of work done on this task."
                  label="Work Completed Details" value="{{$entry->description}}">

    </x-form-input>
    <x-form-input name="hours" icon="hourglass" placeholder="Number of Hours (decimals allowed)"
                  label="Enter Hours Worked" value="{{$entry->hours}}">
        Enter the total number of hours worked (including decimals)
    </x-form-input>
    @props(['bill' => [1 => 'Yes', 0 => 'No']])
    <x-form-select name="billable" :options="$bill" selected="{{$entry->billable}}"
                   icon="money"
                   label="Work Billable?">
        Is this work billable? If not, it will be listed on the invoice as a $0.00 amount.

    </x-form-select>
    <div class="row">
        <div class="col-lg-12">
            @if($entry->id && !$entry->invoice)
                <a class="confirm btn btn-outline-danger btn-sm"
                   data-message="Are you sure you want to delete this time entry?"
                   href="/admin/projects/{{$project->id}}/tasks/{{$task->id}}/entries/{{$entry->id}}"
                   data-method="DELETE">
                    <i class="fa fa-trash"></i> Remove Entry
                </a>
            @endif
            <button type="submit" name="submit" class="btn btn-primary btn-sm pull-right ladda" data-style="expand-left">
                <i class="fa fa-save"></i> Save
            </button>
        </div>
    </div>
</form>
