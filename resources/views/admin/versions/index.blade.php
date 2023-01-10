@extends('layouts.admin', ['title' => 'Logic Version Control'])


@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Version Control/Upgrade</h1>
            <small class="text-muted">See recent changes to Logic and upgrade to latest version.</small>
        </div>

    </div> <!-- .row end -->

@endsection

@section('content')
    <div class="row">

        <div class="row g-3 justify-content-lg-between">
            <div class="offset-2 col-xl-8 col-lg-8 col-md-8 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title"><i class="fa fa-hand-o-right me-2"></i>Logic Changelog</h5>
                    </div>

                    @foreach($versions as $version)
                        @if($version->latest)

                    <div class="card-body">
                        <h6 class="d-inline-block mb-0"><span class="badge bg-{{bm()}}info fw-light">v{{$version->version}}</span></h6>
                        <span class="text-muted">&nbsp;{{\Carbon\Carbon::parse($version->stamp)->format("m/d/y")}} - {{$version->summary}}
                        @if(setting('version') != $version->version)
                            <br/><br/><a class="m-3 btn btn-{{bm()}}success confirm"
                               data-method="GET"
                               data-message="Are you sure you want to upgrade to v{{$version->version}}? Upon clicking proceed, the upgrade will be scheduled and will be done within the next 60 seconds."
                               href="/admin/upgrade"><i class="fa fa-angle-up"></i> Upgrade to v{{$version->version}}</a>
                            @else
                                <a class=" btn btn-sm btn-{{bm()}}success m-3" href="#">You are running the latest version.</a>
                        @endif
                        </span>
                        <ul class="ms-5">
                            <li class="mb-3">
                                <h6 class="fw-bold text-primary">Feature Enhancements</h6>
                                <ul>
                                    @foreach($version->entries as $entry)
                                        @if($entry->type == 'feature')
                                    <li>{{\Carbon\Carbon::parse($entry->stamp)->format("m/d/y")}} - #{{$entry->issue ?? $entry->hash}} :: {{$entry->item}}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>

                            <li class="mb-3">
                                <h6 class="fw-bold text-danger">Bug Fixes</h6>
                                <ul>
                                    @foreach($version->entries as $entry)
                                        @if($entry->type == 'bug')
                                            <li>{{\Carbon\Carbon::parse($entry->stamp)->format("m/d/y")}} - #{{$entry->issue ?? $entry->hash}} :: {{$entry->item}}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        </ul>
                    </div>
                        @else

                            <a class="px-4 py-2" data-bs-toggle="collapse" href="#v_{{$version->id}}" role="button" aria-expanded="false">
                                <h6 class="d-inline-block mb-0"><span class="badge bg-{{bm()}}info fw-light">v{{$version->version}}</span>
                                    @if(currentVersion() == $version->version) <span class="badge bg-{{bm()}}info">running version</span>@endif
                                </h6>
                                <span class="text-muted">&nbsp;{{\Carbon\Carbon::parse($version->stamp)->format("m/d/y")}} - {{$version->summary}}</span>
                            </a>
                            <div class="collapse" id="v_{{$version->id}}">
                                <div class="card-body">
                                    <ul class="ms-5">
                                        <li class="mb-3">
                                            <h6 class="fw-bold text-primary">Feature Enhancements</h6>
                                            <ul>
                                                @foreach($version->entries as $entry)
                                                    @if($entry->type == 'feature')
                                                        <li>{{\Carbon\Carbon::parse($entry->stamp)->format("m/d/y")}} - #{{$entry->issue ?? $entry->hash}} :: {{$entry->item}}</li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </li>

                                        <li class="mb-3">
                                            <h6 class="fw-bold text-danger">Bug Fixes</h6>
                                            <ul>
                                                @foreach($version->entries as $entry)
                                                    @if($entry->type == 'bug')
                                                        <li>{{\Carbon\Carbon::parse($entry->stamp)->format("m/d/y")}} - #{{$entry->issue ?? $entry->hash}} :: {{$entry->item}}</li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        @endif

                    @endforeach

                </div>
            </div>
        </div>
    </div>





@endsection
