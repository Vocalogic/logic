@extends('layouts.admin', ['title' => 'My Partners', 'crumbs' => ['My Partners']])


@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Active Logic Partners</h1>
            <small class="text-muted">Exchange leads with other Logic Service Providers</small>
        </div>

    </div> <!-- .row end -->

@endsection

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <a class="live btn btn-{{bm()}}primary" data-title="Invite new Partner" href="/admin/partners/create"><i class="fa fa-plus"></i> Invite new Partner</a>
            @include('admin.partners.list')
        </div>

        <div class="col-lg-6">
            <div class="card border-primary">
                <div class="card-body">
                    <h5 class="card-title">Your Partner Code</h5>
                    <p class="card-text">
                        To setup a partnership with another Logic Service Provider, you can either send an invite to them if you
                        have their partnership code, or you can provide them yours to get started.
                    </p>
                    <p class="mt-3">
                        Your Partnership Code: <h2 class="text-primary strong">{{license()->partner_code}}</h2>
                    </p>
                </div>
            </div>
        </div>
    </div>

@endsection
