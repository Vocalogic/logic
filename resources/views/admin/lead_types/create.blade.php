@extends('layouts.admin', ['title' => 'Lead Types', 'crumbs' => [
     '/admin/lead_types' => "Lead Types",
     $type->name ?: "Create new Lead Type"
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Lead Types</h1>
            <small class="text-muted">Select the types of leads (used for Discovery Questions)</small>
        </div>
    </div> <!-- .row end -->

@endsection
@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">

                    <form method="POST" action="{{$type->id ? "/admin/lead_types/$type->id" : "/admin/lead_types"}}">
                        @method($type->id ? "PUT" : "POST")
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <x-form-input name="name" value="{{$type->name}}" label="Lead Type Name"
                                              icon="file-text-o">
                                    Enter the type of lead for categorization.
                                </x-form-input>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-12">
                                @if($type->id)
                                    <a class="confirm text-danger" data-message="Are you sure you want to delete this lead type?"
                                       data-method="DELETE" href="/admin/lead_types/{{$type->id}}"><i class="fa fa-trash"></i> Remove Lead Type</a>
                                @endif
                                <button type="submit" class="btn btn-primary ladda pull-right" data-style="zoom-out">
                                    <i class="fa fa-save"></i> Save Lead Type</button>

                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
