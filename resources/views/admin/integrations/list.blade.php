<div class="row row-cols-lg-4 row-cols-md-3 row-cols-sm-2 row-cols-2 g-3 row-deck">
    @foreach(\App\Models\Integration::byCategory($type) as $int)
        <div class="col">
            <div class="card ribbon {{$int->enabled ? "border-success" : "border-danger"}}">
                @if(!$int->enabled)
                    <div class="option-12 bg-danger position-absolute text-light"><i class="fa fa-times"></i></div>
                @else
                    <div class="option-12 bg-success position-absolute text-light"><i class="fa fa-check"></i></div>
                @endif
                <div class="card-body">
                    <img height="300" src="{{$int->connect->getLogo()}}" class="img-fluid rounded"
                         alt="{{$int->connect->getName()}}">
                    <p class="mt-2">{{$int->connect->getDescription()}}</p>
                    <h5 class="mt-1 text-center">
                        @if(!$int->enabled)
                            <a class="btn btn-primary confirm" href="/admin/integrations/{{$int->ident}}/enable"
                               data-method="GET"
                               data-message="Are you sure you want to enable {{$int->connect->getName()}}">
                                <i class="fa fa-check"></i> Enable {{$int->connect->getName()}}
                            </a>
                        @else
                            <a href="/admin/integrations/{{$int->ident}}">Configure {{$int->connect->getName()}}
                                <i class="fa fa-edit"></i>
                            </a>
                        @endif
                    </h5>
                </div>
            </div>
        </div>

    @endforeach

</div>
