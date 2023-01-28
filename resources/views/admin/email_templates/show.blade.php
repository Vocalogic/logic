@extends('layouts.admin', ['title' => 'Email Templates', 'crumbs' => [
     "/admin/email_templates" => "Email Templates",
     $template->name
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">{{$template->name}}</h1>
            <small class="text-muted">{{$template->description}}</small>
        </div>
    </div> <!-- .row end -->

@endsection
@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="/admin/email_templates/{{$template->id}}">
                        @method('PUT')
                        @csrf
                        <div class="row g-3 mb-4">
                            <div class="col-lg-12 col-md-12">

                                <div class="form-floating">
                                    <input type="text" class="form-control" name="subject"
                                           value="{{$template->subject}}">
                                    <label>Subject:</label>
                                    <span class="helper-text">Enter the subject for the email</span>
                                </div>

                                <div class="form-floating">
                                    <textarea class="form-control" name="body" rows="30" style="height: 300px;">{{$template->body}}</textarea>
                                    <label>Email Body:</label>
                                    <span class="helper-text">Enter the body of the email</span>
                                </div>

                                <div class="form-check mt-2 mb-2">
                                    <input class="form-check-input" name="enabled" type="checkbox"
                                           value="1" id="enabledBox" {{$template->enabled ? "checked" : null}}>
                                    <label class="form-check-label" for="enabledBox">Template Enabled?</label>
                                    <span class="helper-text">If not enabled, this email will never send.</span>
                                </div>


                                <div class="form-check mt-2 mb-2">
                                    <input class="form-check-input" name="ticket_enabled" type="checkbox"
                                           value="1" id="enabledBox2" {{$template->ticket_enabled ? "checked" : null}}>
                                    <label class="form-check-label" for="enabledBox2">Ticket Template Enabled?</label>
                                    <span class="helper-text">If enabled (and applicable), a ticket will be created/updated.</span>
                                </div>



                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary mt-3 ladda pull-right" data-style="zoom-out">
                                    <i class="fa fa-save"></i> Update Template
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
