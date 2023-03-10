<p>
    <code>Note!</code> The answer provided here will be shown to the customer as well on their pre-sales dashboard.
</p>
<form method="POST" action="/admin/leads/{{$lead->id}}/discovery/{{$discovery->id}}">
    @method('POST')
    @csrf
    @if($discovery->type == 'Small Text')
        <x-form-input name="value" label="{{$discovery->question}}" icon="question" value="{{$lead->getDiscoveryAnswer($discovery)}}"/>
    @endif
    @if($discovery->type == 'Large Text')
        <x-form-input type='textarea' name="value" label="{{$discovery->question}}" icon="question" value="{{$lead->getDiscoveryAnswer($discovery)}}"/>
    @endif
    @if($discovery->type == 'Dropdown')
        @props(['opts' => explode(",", $discovery->opts)])
        <x-form-select valuesAsKeys="true" name="value" label="{{$discovery->question}}" icon="question" selected="{{$lead->getDiscoveryAnswer($discovery)}}" :options="$opts"/>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <button type="submit" name="submit" class="btn btn-primary ladda" data-style="expand-left">
                <i class="fa fa-save"></i> Save Answer
            </button>
        </div>
    </div>

</form>
