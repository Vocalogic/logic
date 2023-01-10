@php
    $options = \App\Models\PackageSectionQuestionOption::where('package_section_question_id', $q['id'])->get();
    if($q['qty_from_answer_id'])
    {
        $answerKey = "q_" . $q['qty_from_answer_id'];
        $rangeQty = $answers[$answerKey] ?? 1;
    }
    else
        {
            $rangeQty = 1;
        }
@endphp


<table class="table">
    <thead>
    <tr>
        @foreach($options as $o)
            <th>{{$o->option}}
            <br>
            <span class="small">{{$o->description}}</span>
            </th>
        @endforeach
    </tr>
    </thead>
    <tbody>
        @foreach(range(1, $rangeQty) as $idx)
            <tr>
                @foreach($options as $o)
                    <td><input class="form-control" wire:model="answers.q_{{$q['id']}}.o_{{$o['id']}}.i_{{$idx}}">
                    </td>
                @endforeach
            </tr>
        @endforeach

    </tbody>
</table>
