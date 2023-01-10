@extends('layouts.shop.main', ['title' => "Checkout", 'crumbs' => [
     "/shop" => "Home",
     "Review Cart"
]])

@section('content')
    <section class="cart-section section-b-space">
        <div class="container-fluid-lg">
    @livewire('shop.guest-cart-component')
        </div>
    </section>
@endsection

