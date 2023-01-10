@extends('layouts.admin', ['title' => "$cart->id Live View", 'crumbs' => [
     $cart->id,
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Live View ({{$cart->id}})</h1>
            <small class="text-muted">Assist Customers in Products, Pricing and More</small>
        </div>

    </div> <!-- .row end -->

@endsection

@section('content')
    @livewire('admin.assistant-component', ['cid' => $cart->id])
@endsection
