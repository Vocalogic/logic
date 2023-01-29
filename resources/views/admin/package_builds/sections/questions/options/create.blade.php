<p class="card-text">
    Create options/tags/etc for dropdowns, product selections and more.
</p>
<div class="card border-primary">
    <div class="card-body">

        <form method="POST" class="optModal"
              action="/admin/package_builds/{{$build->id}}/sections/{{$section->id}}/questions/{{$question->id}}/options/{{$option->id ?: null}}">
            @csrf
            @method($option->id ? 'PUT' : "POST")

            <div class="row">

                @if($question->type == 'select')
                    <div class="col-lg-12">
                        <x-form-input name="option" value="{{$option->option}}" label="Dropdown Option" icon="outdent">
                            Enter a value for your dropdown (used for comparisons in logic operations)
                        </x-form-input>
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
                <div class="col-lg-6">
                    @if($option->id)

                        <a class="text-danger confirm"
                           href="/admin/package_builds/{{$build->id}}/sections/{{$section->id}}/questions/{{$question->id}}/options/{{$option->id}}"
                           data-method="DELETE"
                           data-message="Are you sure you want to remove this option?">
                            <i class="fa fa-times"></i> Remove Option
                        </a>
                    @endif
                </div>
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary pull-right ladda" data-style="zoom-out">
                        <i class="fa fa-save"></i> Save Option</button>
                </div>
            </div>
        </form>

    </div>
</div>
