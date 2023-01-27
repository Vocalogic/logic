<div>
    @if(!$float)
        <div class="row mb-3">
            <div class="col-lg-4">
                <label class="form-label">{{$label}}</label>
            </div>
            <div class="col-lg-8">
                <fieldset class="form-icon-group left-icon position-relative">
                    <input type="{{$type}}" class="form-control" name="{{$name}}" placeholder="{{$placeholder}}" value="{{$value}}">
                    <div class="form-icon position-absolute">
                        <i class="fa fa-{{$icon}}"></i>
                    </div>
                </fieldset>
                <span class="helper-text">{!! $slot !!}</span>
            </div>
        </div>
    @else
        <div class="form-floating">
            <input type="{{$type}}" class="form-control" name="{{$name}}" value="{{$value}}">
            <label>{{$label}}</label>
            <span class="helper-text">{!! $slot !!}</span>
        </div>
    @endif
</div>
