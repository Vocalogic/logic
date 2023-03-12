@extends('layouts.shop.main', ['title' => "My Projects", 'crumbs' => [
     "/shop" => "Home",
     "/shop/account" => "My Account",
     "Projects",

]])

@section('content')
    <section class="user-dashboard-section section-b-space">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-xxl-3 col-lg-4">
                    @include('shop.account.menu')
                </div>

                <div class="col-xxl-9 col-lg-8">
                    <div class="dashboard-right-sidebar">

                        <div class="dashboard-profile">
                            <div class="title">
                                <h2>My Active Projects</h2>
                                <span class="title-leaf">
                                            <svg class="icon-width bg-gray">
                                                <use xlink:href="/ec/assets/svg/leaf.svg#leaf"></use>
                                            </svg>
                                        </span>
                            </div>
                        </div>


                        <section class="faq-box-contain">
                            <section class="user-dashboard-section section-b-space" style="padding:0px;">
                                <div class="dashboard-right-sidebar">
                                    <div class="dashboard-home">
                                        <div class="total-box">
                                            <div class="row g-sm-4 g-3">
                                                @foreach($account->projects as $project)
                                                    @include('shop.account.projects.single', ['project' => $project])
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
