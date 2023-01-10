@extends('layouts.shop.main', ['title' => "Checkout", 'crumbs' => [
     "/shop" => "Home",
     "Checkout"
]])

@section('content')
    @livewire('shop.checkout-component', ['quote' => $quote])
@endsection

