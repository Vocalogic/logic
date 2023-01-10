<div>
    @if(!$float)
        <div class="row">
            <div class="col-lg-4">
                <label class="form-label">{{$label}}</label>
            </div>
            <div class="col-lg-8">
                <fieldset class="form-icon-group left-icon position-relative">
                    <input type="text" class="form-control" name="{{$name}}">
                    <div class="form-icon position-absolute">
                        <i class="fa fa-{{$icon}}"></i>
                    </div>
                </fieldset>
                <span class="helper-text">{!! $slot !!}</span>
            </div>
        </div>
    @else
        <div class="form-floating">
            <input type="text" class="form-control" name="{{$name}}">
            <label>{{$label}}</label>
            <span class="helper-text">{!! $slot !!}</span>
        </div>
    @endif
</div>
