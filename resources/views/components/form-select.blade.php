<div>
    @if(!$float)
        <div class="row mt-3">
            <div class="col-lg-4">
                <label class="form-label">{{$label}}</label>
            </div>
            <div class="col-lg-8">

                <div class="input-group flex-nowrap">
                    <span class="input-group-text" id="addon-wrapping"><i class="fa fa-{{$icon}}"></i></span>
                    <select class="form-select" name="{{$name}}">
                        @foreach($options as $key => $option)
                            @if($key == $selected)
                                <option value="{{$key}}" selected>{{$option}}</option>
                            @else
                                <option value="{{$key}}">{{$option}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <span class="helper-text">{!! $slot !!}</span>
            </div>
        </div>
    @else
        <div class="form-floating">
            <select class="form-select" name="{{$name}}">
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
