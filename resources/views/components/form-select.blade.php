<div>
    @if(!$float)
        <div class="row">
            <div class="col-lg-4">
                <label class="form-label">{{$label}}</label>
            </div>
            <div class="col-lg-8">
                <fieldset class="form-icon-group left-icon position-relative">
                    <select class="form-control" name="{{$name}}">
                        @foreach($options as $key => $option)
                            @if($key == $selected)
                                <option value="{{$key}}" selected>{{$option}}</option>
                            @else
                            <option value="{{$key}}">{{$option}}</option>
                            @endif
                        @endforeach
                    </select>
                    <div class="form-icon position-absolute">
                        <i class="fa fa-{{$icon}}"></i>
                    </div>
                </fieldset>
                <span class="helper-text">{!! $slot !!}</span>
            </div>
        </div>
    @else
        <div class="form-floating">
            <select class="form-control" name="{{$name}}">
                @foreach($options as $key => $option)
                    @if($key == $selected)
                        <option value="{{$key}}" selected>{{$option}}</option>
                    @else
                        <option value="{{$key}}">{{$option}}</option>
                    @endif
                @endforeach
            </select>
            <label>{{$label}}</label>
            <span class="helper-text">{!! $slot !!}</span>
        </div>
    @endif
</div>
