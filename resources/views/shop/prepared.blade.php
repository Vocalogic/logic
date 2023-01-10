@extends('layouts.shop.main', ['title' => "Review Quote", 'crumbs' => [
     "/shop" => "Home",
     "Review Quote"
]])

@section('content')

    <section class="breadscrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadscrumb-contain breadscrumb-order">
                        <div class="order-box">
                            <div class="order-image">
                                <img src="/ec/assets/images/inner-page/order-success.png" class="blur-up lazyloaded"
                                     alt="">
                            </div>

                            <div class="order-contain">
                                <h3 class="theme-color">Your Quote for {{$quote->lead->company}} is Ready</h3>
                                <a class="btn btn-theme btn-lg" href="/shop/prepared/{{$quote->hash}}/download"><i class="fa fa-download"></i> &nbsp; Download your Quote</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
