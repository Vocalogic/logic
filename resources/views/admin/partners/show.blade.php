@extends('layouts.admin', ['title' => $partner->name])


@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Partnership with {{$partner->name}}</h1>
            <small class="text-muted">Exchange leads with {{$partner->name}}</small>
        </div>

    </div> <!-- .row end -->

@endsection

@section('content')
    @if(!$partner->invited_on)
        @include('admin.partners.invite')
    @elseif(!$partner->accepted_on && $partner->originated_self)
        @include('admin.partners.wait')
    @elseif(!$partner->accepted_on && !$partner->originated_self)
        @include('admin.partners.accept')
    @elseif($partner->accepted_on)
        @include('admin.partners.detail')

    @endif

@endsection
