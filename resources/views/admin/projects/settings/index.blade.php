<h5>Project Settings</h5>
<form method="POST" action="/admin/projects/{{$project->id}}">
    @method('PUT')
    @csrf
    <x-form-input float="true" name="name" value="{{$project->name}}" label="Project Name">
        Enter a project name/title
    </x-form-input>

    <x-form-input type="textarea" float="true" name="description" value="{{$project->description}}"
                  label="Short Description">
        Enter a short description of this project.
    </x-form-input>

    @props(['method' => [
        'Static' => "Fixed Price",
        'Hourly' => "Hourly Rate",
        'Mixed' => "Mixed"
        ]])
    <x-form-select float="true" name="bill_method" :options="$method" selected="{{$project->bill_method}}"
                   label="Project Billing Method">
        How is this project being billed?
    </x-form-select>

    @if($project->bill_method == 'Hourly' || $project->bill_method == 'Mixed')
        <x-form-input float="true" name="project_hourly_rate" label="Project Default Hourly Rate"
                      value="{{moneyFormat($project->project_hourly_rate)}}">
            Enter the default hourly rate for this project.
        </x-form-input>
    @endif

    @if($project->bill_method == 'Static' || $project->bill_method == 'Mixed')
        @if($project->bill_method == 'Static')
            <x-form-input float="true" name="static_price" label="Project Fixed Price"
                          value="{{moneyFormat($project->static_price)}}">
                Enter a total project amount to quote for this project.
            </x-form-input>
        @else
            <x-form-input float="true" name="static_price" label="Project Base Price"
                          value="{{moneyFormat($project->static_price)}}">
                Enter the base price for the project. Hourly/Categories will be added.
            </x-form-input>
        @endif
    @endif

    <div class="col-lg-12">
        <div class="form-floating">
            <input type="text" id="duedate-field" class="form-control" data-provider="flatpickr"
                   placeholder="Due date" required name="start_date"
                   value="{{$project->start_date?->format("Y-m-d")}}"/>
            <label>Starting Date</label>
            <span class="helper-text">Enter the start date for this project</span>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-floating">
            <input type="text" id="duedate-field" class="form-control" data-provider="flatpickr"
                   placeholder="Due date" required name="end_date"
                   value="{{$project->end_date?->format("Y-m-d")}}"/>
            <label>Ending Date</label>
            <span class="helper-text">Enter the end date for this project</span>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <button type="submit" name="save" class="btn btn-primary btn-sm ladda pull-right"
                    data-style="expand-left">
                <i class="fa fa-save"></i> Save Settings
            </button>
        </div>
    </div>

</form>
