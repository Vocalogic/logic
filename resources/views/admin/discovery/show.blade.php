<p>
    Edit the discovery question below. This question will be asked or provided any time this lead type
    is assigned.
</p>
<form method="POST" action="/admin/discovery/{{$discovery->id}}">
    @method('PUT')
    @csrf

    <x-form-input name="question" value="{{$discovery->question}}" label="Question:" icon="question">
        Enter the question to ask.
    </x-form-input>

    @props(['opts' => ['Small Text' => 'Small Text', 'Large Text' => 'Large Text', 'Dropdown' => 'Dropdown']])
    <x-form-select name="type" selected="{{$discovery->type}}" :options="$opts" label="Question Type:" icon="superscript">
        Select the type of question
    </x-form-select>

    <x-form-input type="textarea" name="help" label="Help/Description" value="{{$discovery->help}}" icon="info">
        Enter a short description to help the user answer this question.
    </x-form-input>

    @if($discovery->type == 'Dropdown')
        <x-form-input name="opts" value="{{$discovery->opts}}" label="Dropdown Options:" icon="filter">
            Enter the options for the dropdown (comma delimited)
        </x-form-input>
    @endif


    <div class="row mt-3">
        <div class="col-lg-12">
            <button type="submit" class="btn btn-primary pull-right ladda" data-style="expand-left">
                <i class="fa fa-save"></i> Save
            </button>
        </div>
    </div>
</form>
