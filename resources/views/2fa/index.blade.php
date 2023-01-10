@extends('layouts.shop.main', ['title' => "Verify Your Account", 'crumbs' => [
     "/shop" => "Home",
     "Account Verification"
]])

@section('content')
    <div class="row">
        <div class="offset-3 col-xxl-6">

            <h3 class="mt-3">{{app('request')->ip()}} Not Recognized</h3>
            <p class="mt-3 text-content">In order to protect your account from unauthorized entry, a period verification is required. This can
                be either your IP address changed, or your verification has expired.
                The IP Address <b>{{app('request')->ip()}}</b> is either not recognized with your account or
                your verification has expired.
            </p>
            @livewire('verification-component', ['tfa' => true])

        </div>

@endsection
