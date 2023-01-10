<div>

    <div class="modal fade" id="{{$name}}" tabindex="-1" role="dialog" aria-labelledby="liveModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-{{$size}}" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{$title}}</h5>
                </div>
                <div class="modal-body {{$name}}">
                    {!! $slot !!}
                </div>
            </div>
        </div>
    </div>


</div>
