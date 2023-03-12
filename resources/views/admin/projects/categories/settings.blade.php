<h5>Category Settings</h5>
<form method="POST" action="/admin/projects/{{$project->id}}/categories/{{$category->id}}">
    @method('PUT')
    @csrf
    <x-form-input float="true" name="name" value="{{$category->name}}" label="Category Name">
        Enter a project name/title
    </x-form-input>

    <x-form-input type="textarea" float="true" name="description" value="{{$category->description}}"
                  label="Short Description">
        Enter a short description of this category.
    </x-form-input>

    @props(['method' => [
        'Static' => "Fixed Price",
        'Hourly' => "Hourly Rate",
        'Mixed' => "Mixed"
        ]])
    <x-form-select float="true" name="bill_method" :options="$method" selected="{{$category->bill_method}}"
                   label="Category Billing Method">
        How is this category being billed?
    </x-form-select>

    @if($category->bill_method == 'Hourly' || $category->bill_method == 'Mixed')
        <x-form-input float="true" name="category_hourly_rate" label="Category Default Hourly Rate"
                      value="{{moneyFormat($category->category_hourly_rate ?: $project->project_hourly_rate)}}">
            Enter the default hourly rate for this category.
        </x-form-input>
    @endif

    @if($category->bill_method == 'Static' || $category->bill_method == 'Mixed')
        @if($category->bill_method == 'Static')
            <x-form-input float="true" name="static_price" label="Category Fixed Price"
                          value="{{moneyFormat($category->static_price)}}">
                Enter a total amount to quote for this category.
            </x-form-input>
        @else
            <x-form-input float="true" name="static_price" label="Category Base Price"
                          value="{{moneyFormat($category->static_price)}}">
                Enter the base price for the category. Hourly tasks will be added.
            </x-form-input>
        @endif
    @endif

    <div class="col-lg-12">
        <div class="form-floating">
            <input type="text" id="duedate-field" class="form-control" data-provider="flatpickr"
                   placeholder="Due date" required name="start_date"
                   value="{{$category->start_date?->format("Y-m-d")}}"/>
            <label>Starting Date</label>
            <span class="helper-text">Enter the start date for this category</span>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-floating">
            <input type="text" id="duedate-field" class="form-control" data-provider="flatpickr"
                   placeholder="Due date" required name="end_date"
                   value="{{$category->end_date?->format("Y-m-d")}}"/>
            <label>Ending Date</label>
            <span class="helper-text">Enter the end date for this category</span>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            <a class="confirm btn btn-sm btn-outline-danger"
               data-message="Are you sure you want to remove all tasks and items and this category?"
               href="/admin/projects/{{$project->id}}/categories/{{$category->id}}"
               data-method="DELETE">
                <i class="fa fa-trash"></i> Delete Category
            </a>
            <button type="submit" name="save" class="btn btn-primary btn-sm ladda pull-right"
                    data-style="expand-left">
                <i class="fa fa-save"></i> Save Settings
            </button>
        </div>
    </div>

</form>

