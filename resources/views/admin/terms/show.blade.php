@extends('layouts.admin', ['title' => $term->name])


@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">{{$term->name}}</h1>
            <small class="text-muted">Manage your Terms of Service for different types of accounts</small>
        </div>

    </div> <!-- .row end -->

@endsection

@section('content')
        @livewire('admin.term-editor-component', ['term' => $term])


@endsection
