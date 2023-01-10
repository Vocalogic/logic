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
                <div class="card-body d-flex align-items-start">

                    <ul class="nav nav-pills custom-horizontal me-2" role="tablist">
                        @foreach(\App\Models\EmailTemplateCategory::orderBy('name')->get() as $cat)
                        <li class="nav-item"><a class="nav-link {{$cat->name == 'Accounts' ? "active" : null}}" data-bs-toggle="tab" href="#c{{$cat->id}}" role="tab">{{$cat->name}}</a></li>
                        @endforeach
                        @foreach(\App\Enums\Core\ModuleRegistry::cases() as $case)
                            @if($case->isEnabled())
                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#{{$case->value}}" role="tab">{{$case->getName()}}</a></li>
                                @endif
                            @endforeach

                    </ul>

                    <div class="tab-content ps-3">
                        @foreach(\App\Models\EmailTemplateCategory::orderBy('name')->get() as $cat)

                        <div class="tab-pane fade {{$cat->name == 'Accounts' ? "show active" : null}}" id="c{{$cat->id}}" role="tabpanel">
                            <table class="table">
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
                                        <td><a href="/admin/email_templates/{{$template->id}}">{{$template->name}}</a>
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
                                                    <td><a href="/admin/email_templates/{{$template->id}}">{{$template->name}}</a>
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
@endsection
