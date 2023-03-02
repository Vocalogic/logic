<div class="card">
    <div class="card-body">
        <h5>Task Settings</h5>
        <form method="POST" action="/admin/projects/{{$project->id}}/tasks/{{$task->id}}">
            @method('PUT')
            @csrf
            <x-form-input float="true" name="name" value="{{$task->name}}" label="Task Name">
                Enter the task to be completed
            </x-form-input>


            @props(['method' => [
                'Static' => "Fixed Price",
                'Hourly' => "Hourly Rate",
                'Mixed' => "Mixed"
                ]])
            <x-form-select float="true" name="bill_method" :options="$method" selected="{{$task->bill_method}}"
                           label="Task Billing Method">
                How is this task being billed?
            </x-form-select>

            @if($task->bill_method == 'Hourly' || $task->bill_method == 'Mixed')
                <x-form-input float="true" name="task_hourly_rate" label="Task Hourly Rate"
                              value="{{moneyFormat($task->task_hourly_rate ?: $task->category->category_hourly_rate)}}">
                    Enter the hourly rate for this task.
                </x-form-input>
            @endif

            @if($task->bill_method == 'Static' || $task->bill_method == 'Mixed')
                @if($task->bill_method == 'Static')
                    <x-form-input float="true" name="static_price" label="Task Fixed Price"
                                  value="{{moneyFormat($task->static_price)}}">
                        Enter a total amount to quote for this task.
                    </x-form-input>
                @else
                    <x-form-input float="true" name="static_price" label="Task Base Price"
                                  value="{{moneyFormat($task->static_price)}}">
                        Enter the base price for this task. Hourly will be added.
                    </x-form-input>
                @endif
            @endif

            <x-form-input float="true" name="est_hours_min"
                          label="Estimated Hours (min)" value="{{$task->est_hours_min}}"
                          placeholder="Minimum Hours Estimated">
                Enter the minimum amount of hours estimated to complete.
            </x-form-input>

            <x-form-input float="true" name="est_hours_max"
                          label="Estimated Hours (max)" value="{{$task->est_hours_max}}"
                          placeholder="Maximum Hours Estimated">
                Enter the maximum amount of hours estimated to complete.
            </x-form-input>



            <div class="row">
                <div class="col-lg-12">
                    <button type="submit" name="save" class="btn btn-primary btn-sm ladda pull-right"
                            data-style="expand-left">
                        <i class="fa fa-save"></i> Save Settings
                    </button>
                </div>
            </div>

        </form>

    </div>
</div>
