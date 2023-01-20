<p class="card-text">
    Logic Operations will execute the adding of items depending on the answers to questions.
</p>
<form method="POST"
      action="/admin/package_builds/{{$build->id}}/sections/{{$section->id}}/questions/{{$question->id}}/logics/{{$logic->id ?: null}}">
    @csrf
    @method($logic->id ? 'PUT' : "POST")

    <div class="row mt-3">

        <div class="col-lg-12">
            <div class="form-floating">
                {!! Form::select('add_item_id', \App\Models\BillItem::selectable(), $logic->add_item_id, ['class' => 'form-control']) !!}
                <label>Select Item to Add:</label>
                <span class="helper-text">Select the item to add if the below parameters are met.</span>
            </div>
        </div>
        <div class="col-lg-12 mt-3 ">
            <div class="form-floating">
                {!! Form::select('add_addon_id', $build->relatedAddons(), $logic->add_addon_id, ['class' => 'form-control']) !!}
                <label>Select Addon to Add:</label>
                <span class="helper-text">Select from a list of addons of products/services already listed in this question.</span>
            </div>
        </div>

    </div>

    <div class="row mt-3">
        <div class="col-lg-6">
            <div class="form-floating">
                {!! Form::select('answer_equates', \App\Models\PackageSectionQuestion::getEquates(), $logic->answer_equates, ['class' => 'form-control', 'id' => 'answer_equates']) !!}
                <label class="form-label" for="answer_equates">If Answer Equates To:</label>
                <span class="helper-text">Select the method to compare answer</span>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-floating">
                <input type="text" name="answer" value="{{$logic->answer}}" class="form-control">
                <label>Answer Given:</label>
                <span class="helper-text">Enter answer to compare.</span>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="form-floating">
                {!! Form::select('qty_from_answer', [1 => 'Yes', 0 => 'No'], $logic->qty_from_answer, ['class' => 'form-control']) !!}
                <label>Add Qty Based on Answer:</label>
                <span class="helper-text">If someone answered 5, the qty of the item selected would be 5.</span>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-floating">
                <input type="text" name="qty" value="{{$logic->qty}}" class="form-control">
                <label>Force QTY Value:</label>
                <span
                    class="helper-text">If you answered no, you can enter a qty value here.</span>
            </div>
        </div>

    </div>


    <div class="row mt-3">
        <div class="col-lg-12">
            <input type="submit" class="btn btn-{{bm()}}primary w-100 btn-block" value="Save Logic">
        </div>
    </div>
</form>
