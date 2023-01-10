@extends('layouts.admin', ['title' => 'Lead Types', 'crumbs' => [
     "Lead Types",
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
        <div class="col-lg-4">
            <div class="card border-primary">
                <div class="card-body">
                    <h6 class="card-title">Lead Types</h6>
                    <p>
                        <code>Lead types</code> are used to categorize different needs for different customers. This will reflect
                        primarily on which questions are asked of the customer as well as which terms of service/contract
                        to use when converting the lead to an account.
                    </p>

                    <table class="table mt-2">
                        <thead>
                        <tr>
                            <th>Type</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(\App\Models\LeadType::orderBy('name')->get() as $type)
                            <tr>
                                <td><a href="/admin/lead_types/{{$type->id}}">{{$type->name}}</a></td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <a class="btn btn-{{bm()}}info" href="/admin/lead_types/create"><i class="fa fa-plus"></i> new lead type</a>

                </div>
            </div>

        </div>

        <div class="col-lg-4">
            @include('admin.lead_types.origins.index')
        </div>

        <div class="col-lg-4">
            @include('admin.lead_types.statuses.index')
        </div>
    </div>
@endsection
