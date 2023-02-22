<form method="post" action="/admin/accounts/{{$account->id}}/items/{{$item->id}}/meta" class="require">
    @csrf
    @method('POST')
    <div class="row">
        @foreach($item->item->meta as $meta)
            @if($meta->per_qty)
                @foreach(range(1,$item->qty) as $idx)

                    <div class="col-lg-12 mt-2">
                        <div class="form-floating">
                            @if($meta->answer_type == 'select')
                                @php
                                    $opts = explode(",",$meta->opts);
                                    $odata = [];
                                    foreach ($opts as $opt)
                                        $odata[$opt] = $opt;
                                @endphp
                                {!! Form::select("a_$meta->id_$idx", $odata, $item->getMetaFor($meta, $idx), ['class' => 'form-control']) !!}
                            @elseif($meta->answer_type == 'input')
                                <input type="text" name="a_{{$meta->id}}_{{$idx}}"
                                       value="{{$item->getMetaFor($meta, $idx)}}"
                                       class="form-control">
                            @else
                                <textarea name="a_{{$meta->id}}_{{$idx}}" style="height:100px;"
                                          class="form-control">{{$item->getMetaFor($meta, $idx)}}</textarea>
                            @endif
                            <label>({{$idx}}) {{$meta->item}}</label>
                            <span class="helper-text">{{$meta->description}}</span>
                        </div>
                    </div>

                @endforeach

            @else
                <div class="col-lg-12 mt-2">
                    <div class="form-floating">
                        @if($meta->answer_type == 'select')
                            @php
                                $opts = explode(",",$meta->opts);
                                $odata = [];
                                foreach ($opts as $opt)
                                    $odata[$opt] = $opt;
                            @endphp
                            {!! Form::select("a_$meta->id", $odata, $item->getMetaFor($meta), ['class' => 'form-control']) !!}
                        @elseif($meta->answer_type == 'input')
                            <input type="text" name="a_{{$meta->id}}" value="{{$item->getMetaFor($meta)}}"
                                   class="form-control">
                        @else
                            <textarea name="a_{{$meta->id}}" style="height:100px;"
                                      class="form-control">{{$item->getMetaFor($meta)}}</textarea>
                        @endif
                        <label>{{$meta->item}}</label>
                        <span class="helper-text">{{$meta->description}}</span>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
    <div class="row mt-3">
        <button type="submit" value="Save" class="btn btn-primary pull-right ladda" data-style="expand-left">
            <i class="fa fa-save"></i> Save Requirements
        </button>
    </div>

</form>
