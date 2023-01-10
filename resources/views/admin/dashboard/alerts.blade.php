<div class="row row-cols-lg-4 row-cols-md-2 row-cols-sm-2 row-cols-1 mb-4 row-deck">
    @foreach($alerts as $alert)
        @if(isset($alert->instance) && !$alert->instance)
            <div class="col">
                <div class="card p-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar rounded-circle no-thumbnail bg-light">
                            <img class="avatar" src="/icons/{{$alert->icon}}.png"></div>
                        <div class="flex-fill ms-3 text-truncate">
                            <div class="small text-uppercase">{{$alert->title}}</div>
                            <div><span class="h6 mb-0 fw-bold">{{$alert->count}}</span>
                                <small><a href="#" data-bs-toggle="popover" data-bs-html="true"
                                          data-bs-title="{{$alert->title}}"
                                          data-bs-content="<div style='color: {{currentMode() == 'dark' ? '#fff' : '#000'}};'><p>{{$alert->description}}</p><table class='table small'><thead><tr>@foreach($alert->headers as $head)<td>{{$head}}</td>@endforeach</tr></thead><tbody>@foreach($alert->data as $row)<tr>@foreach($row as $cell)<td>{!! $cell !!}</td>@endforeach</tr>@endforeach</tbody></table></div>">View
                                        Details</a></small></div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

</div>
