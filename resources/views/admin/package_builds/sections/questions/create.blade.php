<p class="card-text">
    Questions are used to gather information that can be used to add items to a quote/cart and to
    collect information required for starting service(s).
</p>
<form method="POST" class="questionmodal"
      action="/admin/package_builds/{{$build->id}}/sections/{{$section->id}}/questions/{{$question->id ?: null}}">
    @csrf
    @method($question->id ? 'PUT' : "POST")
    <div class="row">
        <div class="col-lg-12">
            <div class="form-floating">
                <input type="text" class="form-control" name="question" value="{{$question->question}}">
                <label>Question:</label>
                <span class="helper-text">Enter the question you want to ask the user.</span>
            </div>
        </div>
    </div>
    <div class="row mt-3">

        <div class="col-lg-4">
            <div class="form-floating">
                {!! Form::select('default_show', [1 => 'Show', 0 => 'Do not show'], $question->default_show, ['class' => 'form-control']) !!}
                <label>Default Mode:</label>
                <span class="helper-text">Should we show this section by default?</span>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-floating">
                {!! Form::select('is_numeric', [1 => 'Yes', 0 => 'No'], $question->is_numeric, ['class' => 'form-control']) !!}
                <label>Is Answer a Number?</label>
                <span class="helper-text">Select if the answer to this question should be numerical.</span>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-floating">
                {!! Form::select('type', \App\Models\PackageSectionQuestion::getTypes(), $question->type, ['class' => 'form-control']) !!}
                <label>Type of Question?</label>
                <span class="helper-text">Select how the user will answer this question.</span>
            </div>
        </div>


    </div>
    <div class="row mt-3">
        <div class="col-lg-4">
            <div class="form-floating">
                {!! Form::select('unless_question_id', \App\Models\PackageSectionQuestion::getSelectable(), $question->unless_question_id, ['class' => 'form-control']) !!}
                <label>Unless Question:</label>
                <span class="helper-text">Override default method if question has a specific answer.</span>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-floating">
                {!! Form::select('question_equates', \App\Models\PackageSectionQuestion::getEquates(), $question->question_equates, ['class' => 'form-control']) !!}
                <label>Equates To:</label>
                <span class="helper-text">Select qualifier to compare answer to.</span>
            </div>
        </div>


        <div class="col-lg-4">
            <div class="form-floating">
                <input type="text" name="question_equates_to" value="{{$question->question_equates_to}}"
                       class="form-control">
                <label>Compared Value:</label>
                <span class="helper-text">Enter the comparing value for the previous question.</span>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="form-floating">
                {!! Form::select('qty_from_answer_id', \App\Models\PackageSectionQuestion::getSelectable(), $question->qty_from_answer_id, ['class' => 'form-control']) !!}
                <label>Repeat Question based on Value from Previous Answer:</label>
                <span class="helper-text">For instance, if a customer says they need 5 licenses, maybe you would want to get the machine name for each license.</span>
            </div>
        </div>

    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            @if($question->id)
                <a class="confirm text-danger" data-message="Are you sure you want to remove this question?"
                   data-method="DELETE"
                   href="/admin/package_builds/{{$build->id}}/sections/{{$section->id}}/questions/{{$question->id}}">
                    <i class="fa fa-times"></i> Remove Question
                </a>
            @endif
            <button type="submit" class="btn btn-primary pull-right ladda" data-style="zoom-out">
                <i class="fa fa-save"></i> Save Question
            </button>

        </div>
    </div>
</form>
