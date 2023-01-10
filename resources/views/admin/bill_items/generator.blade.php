@extends('layouts.admin', ['title' => "Generate Marketing", 'crumbs' => $crumbs])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Generate Marketing for {{$item->name}}</h1>
            <small class="text-muted">Use OpenAI To Generate Marketing and Invoicing Text</small>
        </div>
    </div> <!-- .row end -->

@endsection

@section('content')
    @livewire('admin.marketing-generator-component', ['item' => $item])
@endsection
