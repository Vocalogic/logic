@extends('layouts.shop.main', ['title' => $item->name, 'crumbs' => [
     "/" => "Home",
     "/shop/$category->slug" => $category->shop_name,
     $item->name
]])

@section('content')


    <!-- Product Left Sidebar Start -->
    <section class="product-section">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-xxl-9 col-xl-8 col-lg-7 wow fadeInUp">
                    @include('shop.category.item.details')

                </div>

                <div class="col-xxl-3 col-xl-4 col-lg-5 d-none d-lg-block wow fadeInUp">
                    @include('shop.category.item.right')

                </div>
            </div>
        </div>
    </section>

    @include('shop.category.item.related')

@endsection
