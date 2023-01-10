@extends('layouts.shop.main', ['title' => $category->shop_name, 'crumbs' => [
     "/" => "Home",
     $category->shop_name
]])

@section('content')
    @livewire('shop.item-component', ['category' => $category])
@endsection
