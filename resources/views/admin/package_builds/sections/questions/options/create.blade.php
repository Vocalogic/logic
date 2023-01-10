<p class="card-text">
    Create options/tags/etc for dropdowns, product selections and more.
</p>
<form method="POST"
      action="/admin/package_builds/{{$build->id}}/sections/{{$section->id}}/questions/{{$question->id}}/options/{{$option->id ?: null}}">
    @csrf
    @method($option->id ? 'PUT' : "POST")

    <div class="row mt-3">

        @if($question->type == 'select')
            <div class="col-lg-12">
                <div class="form-floating">
                    <input type="text" name="option" value="{{$option->option}}" class="form-control" id="option">
                    <label for="option">Enter Dropdown Option:</label>
                    <span class="helper-text">Enter a value for your dropdown (used for comparisons in logic)</span>
                </div>
            </div>
            @elseif($question->type == 'multi')

            <div class="col-lg-6">
                <div class="form-floating">
                    <input type="text" name="option" value="{{$option->option}}" class="form-control">
                    <label>Enter Field to Capture:</label>
                    <span class="helper-text">Enter the field you wish to capture here</span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-floating">
                    <input type="text" name="description" value="{{$option->description}}" class="form-control">
                    <label>Field Description (help):</label>
                    <span class="helper-text">Enter what this field is used for</span>
                </div>
            </div>

            @elseif($question->type == 'product')
            <div class="col-lg-12">
                <div class="form-floating">
                    {!! Form::select('option', \App\Models\Tag::selectable(), $option->option, ['class' => 'form-control', 'id' => 'option']) !!}
                    <label class="form-label" for="option">Select Tag to Add:</label>
                    <span class="helper-text">Products and Services will be shown with tags selected.</span>
                </div>
            </div>

           @endif




    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            <input type="submit" class="btn btn-{{bm()}}primary w-100 btn-block" value="Save Option">
        </div>
    </div>
</form>
