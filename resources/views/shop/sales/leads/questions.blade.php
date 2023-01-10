<form method="POST" action="/sales/leads/{{$lead->id}}/questions">
    @csrf
    @method('POST')
    <div class="row g-4 mb-1">

        @foreach(\App\Models\Discovery::where('lead_type_id', $lead->lead_type_id)->get() as $d)
            <div class="col-xxl-12">
                <div class="form-floating theme-form-floating">
                    @if($d->type == 'Small Text')
                        <input type="text" class="form-control" id="d_{{$d->id}}" name="d_{{$d->id}}"
                               value="{{$lead->getDiscoveryAnswer($d)}}">
                    @elseif($d->type == 'Dropdown')
                        {!! Form::select("d_$d->id", $d->selectable, $lead->getDiscoveryAnswer($d), ['class' => 'form-control', 'id' => "d_$d->id"]) !!}
                    @elseif($d->type == 'Large Text')
                        <textarea name="d_{{$d->id}}" id="d_{{$d->id}}"
                                  class="form-control">{{$lead->getDiscoveryAnswer($d)}}</textarea>
                    @endif
                    <label for="d_{{$d->id}}">{{$d->question}}</label>
                    <small>{{$d->help}}</small>
                </div>
            </div>
        @endforeach

    </div>
    <div class="row mt-2">
        <div class="col-xxl-6">
            <input type="submit" class="btn bg-primary text-white btn-md fw-bold  w-100" value="Save Questionnaire"/>
        </div>
    </div>
</form>
