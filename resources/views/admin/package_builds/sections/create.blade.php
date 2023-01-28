<p class="card-text">
    Sections (or steps) can be used to gather information and determine if other steps should be
    given to the customer based on answers they have given to previous questions.
</p>
<form method="POST" action="/admin/package_builds/{{$build->id}}/sections/{{$section->id ?: null}}">
    @csrf
    @method($section->id ? 'PUT' : "POST")
    <div class="row">
        <div class="col-lg-8">
            <div class="form-floating">
                <input type="text" class="form-control" name="name" value="{{$section->name}}">
                <label>Section Name:</label>
                <span class="helper-text">Enter the name of this step. (Requirements Gathering, etc)</span>
            </div>
        </div>


        <div class="col-lg-4">
            <div class="form-floating">
                {!! Form::select('default_show', [1 => 'Show', 0 => 'Do not show'], $section->default_show, ['class' => 'form-control']) !!}
                <label>Default Mode:</label>
                <span class="helper-text">Should we show this section by default?</span>
            </div>
        </div>
    </div>
    <div class="row mt-3">


        <div class="col-lg-4">
            <div class="form-floating">
                {!! Form::select('unless_question_id', \App\Models\PackageSectionQuestion::getSelectable(), $section->unless_question_id, ['class' => 'form-control']) !!}
                <label>Unless Question:</label>
                <span class="helper-text">Override default method if question has a specific answer.</span>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-floating">
                {!! Form::select('question_equates', \App\Models\PackageSectionQuestion::getEquates(), $section->question_equates, ['class' => 'form-control']) !!}
                <label>Equates To:</label>
                <span class="helper-text">Select qualifier to compare answer to.</span>
            </div>
        </div>


        <div class="col-lg-4">
            <div class="form-floating">
                <input type="text" name="question_equates_to" value="{{$section->question_equates_to}}"
                       class="form-control">
                <label>Compared Value:</label>
                <span class="helper-text">Enter the comparing value for the previous question.</span>
            </div>
        </div>


    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="form-floating">
                <textarea name="description" class="form-control" style="height:150px;">{!! $section->description !!}</textarea>
                <label>Section Description (Customer Help):</label>
                <span class="helper-text">Enter the information you want to display before asking questions in this section.</span>
            </div>

        </div>
    </div>
    <div class="row mt-3">
        <div class="col-lg-6">
            @if($section->id)
                <a class="confirm text-danger" data-message="Are you sure you want to remove this section?"
                   data-method="DELETE"
                   href="/admin/package_builds/{{$build->id}}/sections/{{$section->id}}">
                    <i class="fa fa-times"></i> Remove Section
                </a>
            @endif
        </div>


        <div class="col-lg-6">
            <input type="submit" class="btn btn-{{bm()}}primary w-100 btn-block" value="Save Section">
        </div>
    </div>
</form>
