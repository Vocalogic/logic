@extends('layouts.shop.main', ['title' => $build->name, 'crumbs' => [
     "/shop" => "Home",
     $build->name
]])

@section('content')
    @livewire('shop.package-component', ['build' => $build])
@endsection

