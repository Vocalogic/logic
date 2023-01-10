@if($lead->partner && !$lead->partner_sourced)
    <p>
        This lead has been assigned to <b>{{$lead->partner->name}}</b> and is read-only. To unlock this lead and
        regain control, the partner must mark the lead as lost. This will break the relationship between you and
        the partner with this lead only.
    </p>
@elseif($lead->partner && $lead->partner_sourced)
    <p>
        This lead originated from <b>{{$lead->partner->name}}</b> and cannot be altered or transferred.
    </p>
@else
    <p>
        <code>WARNING!</code> If you set a lead to a partner you will no longer have access to this lead except
        to see updates, quotes, and communication with the partner. This can <b>only be undone</b> by the partner
        closing the lead as lost. Be cautious when setting!
    </p>
    <p class="text-center">
        <code>Make sure you have all relevant lead information
            set before sending!</code>
    </p>
    <form method="POST" action="/admin/leads/{{$lead->id}}/partner">
        @csrf
        @method('POST')
        <div class="row mt-3">
            <div class="col-lg-12">
                <div class="form-floating">
                    {!! Form::select('partner_id', \App\Models\Partner::getSelectable(), null, ['class' => 'form-control']) !!}
                    <label>Select Partner</label>
                    <span class="helper-text">Select the partner to send this lead to</span>
                </div>
                <input type="submit" class="btn btn-{{bm()}}primary mt-3" value="Set and Send to Partner">
            </div>
        </div>
    </form>

@endif
