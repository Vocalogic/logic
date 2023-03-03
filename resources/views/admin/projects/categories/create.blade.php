<p>
    Task categories are used to define grouped tasks that relate to the same objective. Categories
    can be billed with a static price, hourly, or a mix of both a base price and hourly.
</p>
<form method="POST" action="/admin/projects/{{$project->id}}/categories{{$category->id ? "/$category->id" : null}}">
    @method($category->id ? "PUT" : "POST")
    @csrf
    <x-form-input name="name" label="Category Name:" placeholder="Discovery Process" value="{{$category->name}}" icon="list">
        Enter a name for this category
    </x-form-input>

    <x-form-input type="textarea" icon="comment" name="description" value="{{$category->description}}"
                  label="Short Description">
        Enter a short description for this category.
    </x-form-input>

    @props(['method' => [
               'Static' => "Fixed Price",
               'Hourly' => "Hourly Rate",
               'Mixed' => "Mixed"
               ]])
    <x-form-select icon="dollar" name="bill_method" :options="$method" selected="{{$category->bill_method}}"
                   label="Category Billing Method">
        How is this category being billed?
    </x-form-select>

    @if($category->bill_method == 'Static' || $category->bill_method == 'Mixed')
        @if($category->bill_method == 'Static')
            <x-form-input icon="money" name="static_price" label="Category Fixed Price"
                          value="{{moneyFormat($category->static_price)}}">
                Enter a total project amount to quote for this category.
            </x-form-input>
        @else
            <x-form-input icon="money" name="static_price" label="Category Base Price"
                          value="{{moneyFormat($category->static_price)}}">
                Enter the base price for this category. Hourly tasks will be added.
            </x-form-input>
        @endif
    @endif

    <div class="row">
        <div class="col-lg-12">
            <button type="submit" name="submit" class="btn btn-primary pull-right ladda" data-style="expand-left">
                <i class="fa fa-save"></i> Save
            </button>
        </div>
    </div>
</form>
