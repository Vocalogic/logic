@extends('layouts.shop.main', ['title' => setting('brand.name') . " Presales for $lead->company", 'crumbs' => [
     "/shop" => "Home",
     $lead->company
]])

@section('content')
    <section class="user-dashboard-section section-b-space">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-xxl-3 col-lg-4">
                    @include('shop.presales.menu')
                </div>

                <div class="col-xxl-9 col-lg-8">
                    <button class="btn left-dashboard-show btn-animation btn-md fw-bold d-block mb-4 d-lg-none">Show
                        Menu
                    </button>
                    <div class="dashboard-right-sidebar">
                        <div class="dashboard-home">
                            <div class="title">
                                <h2>{{setting('brand.name')}} Pre-Sales Review</h2>
                                <span class="title-leaf">
                                            <svg class="icon-width bg-gray">
                                                <use xlink:href="/ec/assets/svg/leaf.svg#leaf"></use>
                                            </svg>
                                        </span>
                            </div>

                            <div class="dashboard-user-name mb-5">
                                <h6 class="text-content">Hi, <b class="text-title">{{$lead->contact}}</b></h6>
                                <p class="text-content">
                                    Here you can review your pre-sales questionaire, see quotes, and communicate with
                                    your sales agent, <b class="text-title">{{$lead->agent->name}}</b>.
                                </p>
                            </div>

                            @include('shop.presales.quote_block')

                            <div class="dashboard-title">
                                <h3>{{$lead->company}} Profile</h3>
                            </div>

                            <div class="row g-4">
                                <div class="col-xxl-6">
                                    <div class="dashboard-contant-title">
                                        <h4>Primary Contact <a class="live" data-title="Edit Contact Information" href="/shop/presales/{{$lead->hash}}/contact">Edit</a>
                                        </h4>
                                    </div>
                                    <div class="dashboard-detail">
                                        <h6 class="text-content">{{$lead->contact}}</h6>
                                        <h6 class="text-content">{{$lead->email}}</h6>
                                        <h6 class="text-content">{{$lead->address}} {{$lead->address2}}</h6>
                                        <h6 class="text-content">{{$lead->city}}, {{$lead->state}} {{$lead->zip}}</h6>
                                        <h6 class="text-content">{{$lead->phone}}</h6>



                                    </div>
                                </div>
                                @if($lead->type)
                                <div class="col-xxl-6">
                                    <div class="dashboard-contant-title">
                                        <h4>Questionnaire <a class="live" data-title="Edit Questionnaire" href="/shop/presales/{{$lead->hash}}/questions">Edit</a></h4>
                                    </div>
                                    <div class="dashboard-detail">
                                        @foreach(\App\Models\Discovery::where('lead_type_id', $lead->lead_type_id)->get() as $d)
                                        <h6 class="text-content"><strong>{{$d->question}}</strong> {{$lead->getDiscoveryAnswer($d)}}</h6>
                                        @endforeach
                                    </div>
                                </div>
                                @endif


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
