@switch($q['type'])
    @case('text')
        <input type="text" class="form-control" id="q_{{$q['id']}}" wire:model="answers.q_{{$q['id']}}">
        @break
    @case('select')
        <select name="q_{{$q['id']}}" class="form-control" wire:model="answers.q_{{$q['id']}}">
            <option value="" selected>-- Select --</option>
            @foreach(\App\Models\PackageSectionQuestionOption::where('package_section_question_id', $q['id'])->get() as $o)
                <option value="{{$o->option}}">{{$o->option}}</option>
            @endforeach
        </select>
        @break
    @case('textarea')
        <textarea class="form-control" id="q_{{$q['id']}}" wire:model="answers.q_{{$q['id']}}"></textarea>
        @break
    @case('multi')
        <h5 class="text-primary text-center">{{$q['question']}}</h5>
        @include('shop.package.multi', ['q' => $q])
        @break
    @case('product')
        <h5 class="text-primary text-center">{{$q['question']}}</h5>
        @include('shop.package.product', ['q' => $q])
        @break

@endswitch

