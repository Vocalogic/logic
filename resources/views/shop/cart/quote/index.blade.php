@extends('layouts.shop.main', ['title' => "Create Quote", 'crumbs' => [
     "/shop" => "Home",
     "Create Quote"
]])

@section('content')
    <section class="cart-section section-b-space">
        <div class="container-fluid-lg">
            @livewire('shop.cart-quote-component')
        </div>
    </section>
@endsection

