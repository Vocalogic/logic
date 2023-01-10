<div class="card ">
    <div class="card-body">
        <p class="text-muted text-uppercase mb-0 small">{{$title}}</p>
        <h5 class="mt-0 mb-3 small"><a href="#" class="text-primary">{{$sub}}</a></h5>

        <form method="POST" action="/admin/settings" enctype="multipart/form-data">
            @csrf
            @method('POST')

            @foreach(\App\Models\Setting::where('category', $tab)->get() as $q)
                <div class="row mb-3">

                    <label for="s_{{$q->id}}" class="col-sm-3 col-form-label">{{$q->question}}</label>
                    <div class="col-sm-9">
                        @if($q->type == 'input')
                            <input type="text" class="form-control" name="s_{{$q->id}}" id="s_{{$q->id}}"
                                   value="{{$q->value ?: $q->default}}">
                        @elseif($q->type == 'number')
                            <input type="number" min="{{explode("|", $q->opts)[0]}}" max="{{explode("|", $q->opts)[1]}}"
                                   class="form-control" name="s_{{$q->id}}" id="s_{{$q->id}}"
                                   value="{{$q->value ?: $q->default}}">
                        @elseif($q->type == 'password')
                            <input type="password" class="form-control" name="s_{{$q->id}}" id="s_{{$q->id}}"
                                   value="{{$q->value ?: $q->default}}">
                        @elseif($q->type == 'file')
                            <input type="file" name="sf_{{$q->id}}" id="s_{{$q->id}}" class="drop" data-default-file="{{$q->value ? _file($q->value)?->relative : null}}">
                        @elseif($q->type == 'select')
                            {!! Form::select("s_$q->id", $q->selectOpts, $q->value, ['class' => 'form-select']) !!}
                        @elseif($q->type == 'tags')
                            <input type="text" class="form-control" data-role="tagsinput" name="s_{{$q->id}}"
                                   id="s_{{$q->id}}" value="{{$q->value ?: $q->default}}">
                        @elseif($q->type == 'textarea')
                            <textarea name="s_{{$q->id}}" class="form-control"
                                      style="height: 200px">{{$q->value ?: $q->default}}</textarea>
                        @elseif($q->type == 'color')
                            <input type="color" name="s_{{$q->id}}" class="form-control form-control-color"
                                   value="{{$q->value ?: $q->default}}" title="Select color">

                        @endif
                        <span class="helper-text">{{$q->help}}</span>
                    </div>
                </div>

            @endforeach
            <div class="row mb-3">
                <div class="col-lg-4">
                    <input type="submit" name="submit" class="btn w-100 btn-primary btn-rounded wait" value="Save">
                </div>
            </div>
        </form>
    </div>
</div>
