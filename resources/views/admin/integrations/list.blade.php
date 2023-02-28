<div class="row row-deck">
    @foreach(\App\Models\Integration::byCategory($type) as $int)
        <div class="col-lg-3 col-sm-4 col-xs-6">
            <div class="card border {{$int->enabled ? "border-success" : "border-danger"}}">

                <div class="card-body">
                    <img src="{{$int->connect->getLogo()}}" class="img-fluid"
                         alt="{{$int->connect->getName()}}">
                    <p class="mt-2">{{$int->connect->getDescription()}}</p>
                </div>
                <div class="card-footer text-center">
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
                </div>
            </div>
        </div>
    @endforeach
</div>
