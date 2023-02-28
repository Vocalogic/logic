@extends('layouts.admin', ['title' => 'Email Templates', 'crumbs' => [
     "Affiliates",
]])

@section('content')
    <div class="row">
        <div class="col-xs-12 col-md-2">
            <a class="live btn btn-primary w-100" href="/admin/affiliates/create"
               data-title="Add New Affiliate">
                <i class="fa fa-plus"></i> Add Affiliate
            </a>
        </div>
        <div class="col-xs-12 col-md-10">
            <div class="card">
                <div class="card-body">
                    <table class="table datatable">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Commission</th>
                            <th>Notes</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\Affiliate::orderBy('name')->get() as $affiliate)
                                <tr>
                                    <td>
                                        <a class="live"
                                           data-title="Edit {{$affiliate->name}}"
                                           href="/admin/affiliates/{{$affiliate->id}}">
                                            {{$affiliate->name}}
                                        </a>
                                        @if($affiliate->company)
                                            <br/><small class="text-muted">{{$affiliate->company}}</small>
                                        @endif
                                    </td>
                                    <td>{{$affiliate->email}}</td>
                                    <td>{{$affiliate->commission}}</td>
                                    <td>{{$affiliate->notes}}</td>
                                </tr>
                                @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
