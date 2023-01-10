@extends('layouts.admin', ['title' => "Configure " . $integration->connect->getName(), 'crumbs' => [
     '/admin/integrations' => "Integrations",
     $integration->connect->getName()
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Configure {{$integration->connect->getName()}}</h1>
            <small class="text-muted">Configure and enable addons for billing, support and more.</small>
        </div>

    </div> <!-- .row end -->

@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="/admin/integrations/{{$integration->ident}}">
                                @method('PUT')
                                @csrf
                                @foreach($integration->getRequirements() as $req)
                                    <div class="row mb-3">
                                        <label for="i_{{$req->var}}"
                                               class="col-sm-4 col-form-label">{{$req->item}}</label>
                                        <div class="col-sm-8">
                                            <input type="{{$req->protected ? "password" : "text"}}" class="form-control"
                                                   id="i_{{$req->var}}"
                                                   name="{{$req->var}}"
                                                   value="{{$integration->unpacked->{$req->var} }}">
                                            <small class="helper-text">{{$req->description}}</small>
                                        </div>
                                    </div>
                                @endforeach
                                <input type="submit" name="submit" class="btn btn-primary wait"
                                       value="Save Configuration">
                                <a class="btn btn-danger confirm"
                                   data-message="Are you sure you want to disable {{$integration->connect->getName()}}"
                                   data-method="GET"
                                   href="/admin/integrations/{{$integration->ident}}/disable">
                                    <i class="fa fa-times"></i> Disable {{$integration->connect->getName()}}
                                </a>
                                @if(\App\Enums\Core\IntegrationRegistry::from($integration->ident)->hasOAuth())
                                    <a class="btn btn-info"
                                       href="{{$integration->connect->getOauthRedirect()}}">
                                        <i class="fa fa-refresh"></i> Authorize {{$integration->connect->getName()}}
                                    </a>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
