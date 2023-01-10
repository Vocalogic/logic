<form method="POST" action="/admin/category/{{$cat->id}}/items/{{$item->id}}/meta{{$meta->id ? "/$meta->id" : null}}">
    @csrf
    @method($meta->id ? "PUT" : "POST")
    <div class="row">
        <div class="col-lg-8">
            <div class="form-floating">
                <input type="text" name="item" value="{{$meta->item}}" class="form-control">
                <label>Requirement Name/Question</label>
                <span class="helper-text">Enter the question you want answered for this requirement.</span>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-floating">
                {!! Form::select('answer_type', ['input' => 'Input', 'select' => 'Select', 'textarea' => "Large Input"], $meta->answer_type, ['class' => 'form-control']) !!}
                <label>Answer Type?</label>
                <span class="helper-text">Select the type of answer requested.</span>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="form-floating">
                <input type="text" name="description" value="{{$meta->description}}" class="form-control">
                <label>Enter help text for requirement</label>
                <span class="helper-text">(i.e. Enter the number of items you wish to have)</span>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-lg-4">
            <div class="form-floating">
                {!! Form::select('per_qty', [1 => 'Yes', 0 => 'No'], $meta->per_qty, ['class' => 'form-control']) !!}
                <label>Require entry per qty?</label>
                <span class="helper-text">Yes = Question asked per qty, No = Question asked once.</span>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-floating">
                {!! Form::select('required_sale', [0 => 'No', 1 => 'Yes'], $meta->required_sale, ['class' => 'form-control']) !!}
                <label>Require Answer for Sale?</label>
                <span class="helper-text">If Yes, customers or agents creating quotes must answer this to add.</span>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-floating">
                {!! Form::select('customer_viewable', [0 => 'No', 1 => 'Yes'], $meta->customer_viewable, ['class' => 'form-control']) !!}
                <label>Customer can see/answer?</label>
                <span class="helper-text">If Yes, customers will be able to see this requirement and answer.</span>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="form-floating">
                <input type="text" name="opts" value="{{$meta->opts}}" class="form-control">
                <label>Options (if Select)</label>
                <span class="helper-text">If a select box, enter comma separated options for the dropdown.</span>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            <input type="submit" class="btn btn-{{bm()}}primary" value="Save">
            @if($meta->id)
                <a href="/admin/category/{{$cat->id}}/items/{{$item->id}}/meta/{{$meta->id}}"
                   class="btn btn-danger confirm"
                    data-message="Are you sure you want to remove this item?"
                   data-method="DELETE">
                    <i class="fa fa-trash"></i> Remove Requirement
                </a>
            @endif
        </div>
    </div>

</form>
