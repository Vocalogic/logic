@extends('layouts.shop.main', ['title' => "Order #{$order->hash}", 'crumbs' => [
     "/shop" => "Home",
     "/shop/account" => "My Account",
     "/shop/account/orders" => "Orders",
     "Order #$order->hash"
]])

@section('content')
    <section class="order-detail">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-xxl-3 mb-3">
                    <a href="/shop/account" class="btn text-white bg-success"><i class="fa fa-arrow-left"></i> &nbsp; Back </a>
                </div>
            </div>
            @if($order->invoice && $order->invoice->balance > 0)
                <div class="row mt-3">
                    <div class="alert alert-warning"><i class="fa fa-info-circle"></i> <b>NOTE:</b> <a href="/shop/account/invoices/{{$order->invoice->id}}">Invoice #{{$order->invoice->id}}</a> has a balance
                    of ${{moneyFormat($order->invoice->balance)}} and order will not be fulfilled until paid.</div>
                </div>
            @endif
            <div class="row g-sm-4 g-3">
                <div class="col-xxl-3 col-xl-4 col-lg-6">
                    <div class="order-image">
                        @if($order->getFirstImage())
                            <img src="{{_file($order->getFirstImage())->relative}}" class="img-fluid blur-up lazyloaded"
                                 alt="">
                        @endif
                    </div>
                </div>

                <div class="col-xxl-9 col-xl-8 col-lg-6">
                    <div class="row g-sm-4 g-3">
                        <div class="col-xl-4 col-sm-6">
                            <div class="order-details-contain">
                                <div class="order-tracking-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="feather feather-package text-content">
                                        <line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line>
                                        <path
                                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                    </svg>
                                </div>

                                <div class="order-details-name">
                                    <h5 class="text-content">Status</h5>
                                    <h2 class="theme-color">{{$order->status}}</h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-sm-6">
                            <div class="order-details-contain">
                                <div class="order-tracking-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="feather feather-truck text-content">
                                        <rect x="1" y="3" width="15" height="13"></rect>
                                        <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                        <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                        <circle cx="18.5" cy="18.5" r="2.5"></circle>
                                    </svg>
                                </div>

                                <div class="order-details-name">
                                    <h5 class="text-content">Tracking (if applicable)</h5>
                                    <h4>{{$order->getFirstShipment()?->tracking ?: "No Tracking Information"}}</h4>

                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-sm-6">
                            <div class="order-details-contain">
                                <div class="order-tracking-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="feather feather-info text-content">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="16" x2="12" y2="12"></line>
                                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                    </svg>
                                </div>

                                <div class="order-details-name">
                                    <h5 class="text-content">Order Processed</h5>
                                    <h4>{{$order->hasBeen(\App\Enums\Core\OrderStatus::Shipped) ? $order->getFirstShipment()->submitted_on->format("m/d/y h:ia") : "Not Shipped"}}</h4>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-sm-6">
                            <div class="order-details-contain">
                                <div class="order-tracking-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="feather feather-crosshair text-content">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="22" y1="12" x2="18" y2="12"></line>
                                        <line x1="6" y1="12" x2="2" y2="12"></line>
                                        <line x1="12" y1="6" x2="12" y2="2"></line>
                                        <line x1="12" y1="22" x2="12" y2="18"></line>
                                    </svg>
                                </div>

                                <div class="order-details-name">
                                    <h5 class="text-content">Expected Arrival</h5>
                                    <h4>{{$order->hasBeen(\App\Enums\Core\OrderStatus::Shipped) ? $order->getFirstShipment()->expected_arrival?->format("M d") : "Unknown"}}</h4>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-sm-6">
                            <div class="order-details-contain">
                                <div class="order-tracking-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="feather feather-map-pin text-content">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                </div>

                                <div class="order-details-name">
                                    <h5 class="text-content">Destination</h5>
                                    <h4>{{$order->account->address}}</h4>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-sm-6">
                            <div class="order-details-contain">
                                <div class="order-tracking-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="feather feather-calendar text-content">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                </div>

                                <div class="order-details-name">
                                    <h5 class="text-content">Order Age</h5>
                                    <h4>{{$order->created_at->diffInDays()}} days</h4>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 overflow-hidden">
                            <ol class="progtrckr">
                                @foreach(\App\Enums\Core\OrderStatus::getStatusList($order->hasShippable()) as $status)
                                    @if($order->hasBeen($status))
                                        <li class="progtrckr-done">
                                            <h5>{{$status}}</h5>
                                            <h6>{{$status->getHelp()}}</h6>
                                        </li>
                                    @else
                                        <li class="progtrckr-todo">
                                            <h5>{{$status}}</h5>
                                            <h6>{{$status->getHelp()}}</h6>
                                        </li>
                                    @endif
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 mt-2">
                <table class="table order-tab-table">
                    <thead>
                    <tr>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Agent</th>
                        <th>Note</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($order->logs()->orderBy('created_at', 'DESC')->get() as $log)
                    <tr>
                        <td>{{$log->status}}</td>
                        <td>{{$log->created_at->format("M d h:i")}}</td>
                        <td>{{$log->user ? $log->user->short : "System"}}</td>
                        <td>{{$log->note}}</td>
                    </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>

        </div>
    </section>

@endsection
