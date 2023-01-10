@extends('layouts.shop.main', ['title' => $quote->name ?: setting('brand.name') . " Quote #$quote->id", 'crumbs' => [
     "/shop" => "Home",
     "Quote #{$quote->id}"
]])

@section('content')
    @livewire('shop.shop-cart-component', ['quote' => $quote])
@endsection
