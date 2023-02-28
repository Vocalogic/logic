@extends('layouts.admin', ['title' => 'Email Templates', 'crumbs' => [
     "Email Templates",
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">E-Mail Templates</h1>
            <small class="text-muted">Modify the verbiage that is used when sending out emails from Logic.</small>
        </div>
    </div> <!-- .row end -->

@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-2">


                            <div class="nav flex-column nav-pills text-center" id="v-pills-tab" role="tablist"
                                 aria-orientation="vertical">
                                @foreach(\App\Models\EmailTemplateCategory::orderBy('name')->get() as $cat)
                                    <a class="nav-link mb-2 {{$loop->first ? "active" : null}}" id="{{$cat->name}}-tab"
                                       data-bs-toggle="pill" href="#c{{$cat->id}}" role="tab"
                                       aria-controls="{{$cat->name}}-home" aria-selected="true">{{$cat->name}}</a>
                                @endforeach
                                @foreach(\App\Enums\Core\ModuleRegistry::cases() as $case)
                                    @if($case->isEnabled())
                                        <a class="nav-link mb-2" id="{{$case->value}}-tab" data-bs-toggle="pill"
                                           href="#{{$case->value}}" role="tab" aria-controls="{{$case->value}}-home"
                                           aria-selected="true">{{$case->getHuman()}}</a>
                                    @endif
                                @endforeach

                            </div>
                        </div>
                        <div class="col-md-10">

                            <div class="tab-content ps-3">
                                @foreach(\App\Models\EmailTemplateCategory::orderBy('name')->get() as $cat)

                                    <div class="tab-pane fade {{$cat->name == 'Accounts' ? "show active" : null}}"
                                         id="c{{$cat->id}}" role="tabpanel">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th width="20%">Name</th>
                                                <th width="40%">Description</th>
                                                <th width="40%">Subject</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($cat->templates()->whereNull('module')->get() as $template)
                                                <tr class="{{$template->enabled ?: "bg-light-danger"}}">
                                                    <td>
                                                        <a href="/admin/email_templates/{{$template->id}}">{{$template->name}}</a>
                                                        @if(preg_match("/placeholder/i", $template->body))
                                                            <span class="badge bg-danger">placeholder</span>
                                                        @endif
                                                    </td>
                                                    <td>{{$template->description}}</td>
                                                    <td>{{$template->subject}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endforeach

                                @foreach(\App\Enums\Core\ModuleRegistry::cases() as $case)
                                    @if($case->isEnabled())
                                        <div class="tab-pane fade" id="{{$case->value}}" role="tabpanel">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th width="20%">Name</th>
                                                    <th width="40%">Description</th>
                                                    <th width="40%">Subject</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach(\App\Models\EmailTemplate::where('module', $case->value)->get() as $template)
                                                    <tr class="{{$template->enabled ?: "bg-light-danger"}}">
                                                        <td>
                                                            <a href="/admin/email_templates/{{$template->id}}">{{$template->name}}</a>
                                                            @if(preg_match("/placeholder/i", $template->body))
                                                                <span class="badge bg-danger">placeholder</span>
                                                            @endif
                                                        </td>
                                                        <td>{{$template->description}}</td>
                                                        <td>{{$template->subject}}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                        </div>


                    </div>
                </div>
            </div>
        </div>
@endsection
