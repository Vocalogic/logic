<div>
    @if(!$float)
        <div class="row mb-3">
            <div class="col-lg-4">
                <label class="form-label">{{$label}}</label>
            </div>
            <div class="col-lg-8">


                <div class="input-group flex-nowrap">
                    <span class="input-group-text" id="addon-wrapping"><i class="fa fa-{{$icon}}"></i></span>
                    <input type="{{$type}}" class="form-control" name="{{$name}}" placeholder="{{$placeholder}}" value="{{$value}}">
                </div>


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

