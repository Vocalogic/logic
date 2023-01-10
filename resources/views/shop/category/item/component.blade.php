<div>
    <div class="row g-4">
        <div class="col-xl-6 wow fadeInUp">
            <div class="product-left-box">
                <div class="row g-sm-4 g-2">
                    <div class="col-xxl-10 col-lg-12 col-md-10 order-xxl-2 order-lg-1 order-md-2">
                        <div class="product-main no-arrow">
                            @if($item->photo_id && _file($item->photo_id)?->relative)

                                <div>
                                    <div class="slider-image">
                                        <img src="{{_file($item->photo_id)->relative}}" id="img-1"
                                             class="img-fluid blur-up lazyload" alt="{{$item->name}}">
                                    </div>
                                </div>
                            @endif

                            @if($item->photo_2 && _file($item->photo_2)?->relative)
                                <div>
                                    <div class="slider-image">
                                        <img src="{{_file($item->photo_2)->relative}}" id="img-2"
                                             class="img-fluid  blur-up lazyload"
                                             alt="{{$item->name}}">
                                    </div>
                                </div>
                            @endif

                            @if($item->photo_3 && _file($item->photo_3)?->relative)
                                <div>
                                    <div class="slider-image">
                                        <img src="{{_file($item->photo_3)->relative}}" id="img-3"
                                             class="img-fluid blur-up lazyload"
                                             alt="{{$item->name}}">
                                    </div>
                                </div>
                            @endif

                            @if($item->photo_4 && _file($item->photo_4)?->relative)
                                <div>
                                    <div class="slider-image">
                                        <img src="{{_file($item->photo_4)->relative}}" id="img-4"
                                             class="img-fluid blur-up lazyload"
                                             alt="{{$item->name}}">
                                    </div>
                                </div>
                            @endif

                            @if($item->photo_5 && _file($item->photo_5)?->relative)
                                <div>
                                    <div class="slider-image">
                                        <img src="{{_file($item->photo_5)->relative}}" id="img-5"
                                             class="img-fluid blur-up lazyload"
                                             alt="{{$item->name}}">
                                    </div>
                                </div>
                            @endif


                        </div>
                    </div>

                    <div class="col-xxl-2 col-lg-12 col-md-2 order-xxl-1 order-lg-2 order-md-1">
                        <div class="left-slider-image left-slider no-arrow slick-top">
                            @if($item->photo_id && _file($item->photo_id)?->relative)
                                <div>
                                    <div class="sidebar-image">
                                        <img src="{{_file($item->photo_id)->relative}}"
                                             class="img-fluid blur-up lazyload" alt="{{$item->name}}">
                                    </div>
                                </div>
                            @endif
                            @if($item->photo_2 && _file($item->photo_2)?->relative)
                                <div>
                                    <div class="sidebar-image">
                                        <img src="{{_file($item->photo_2)->relative}}"
                                             class="img-fluid blur-up lazyload" alt="{{$item->name}}">
                                    </div>
                                </div>
                            @endif

                            @if($item->photo_3 && _file($item->photo_3)?->relative)
                                <div>
                                    <div class="sidebar-image">
                                        <img src="{{_file($item->photo_3)->relative}}"
                                             class="img-fluid blur-up lazyload" alt="{{$item->name}}">
                                    </div>
                                </div>
                            @endif

                            @if($item->photo_4 && _file($item->photo_4)?->relative)
                                <div>
                                    <div class="sidebar-image">
                                        <img src="{{_file($item->photo_4)->relative}}"
                                             class="img-fluid blur-up lazyload" alt="{{$item->name}}">
                                    </div>
                                </div>
                            @endif

                            @if($item->photo_5 && _file($item->photo_5)?->relative)
                                <div>
                                    <div class="sidebar-image">
                                        <img src="{{_file($item->photo_5)->relative}}"
                                             class="img-fluid blur-up lazyload" alt="{{$item->name}}">
                                    </div>
                                </div>
                            @endif


                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-6 wow fadeInUp">
            <div class="right-box-contain">
                @if($errorMessage)
                    <div class="alert alert-danger">{!! $errorMessage !!}</div>
                @endif

                @if(auth()->guest() && $item->msrp_note)
                    <h6 class="offer-top">{{$item->msrp_note}}</h6>
                @endif
                <h2 class="name">{{$item->name}}</h2>
                <div class="price-rating">
                    @if(auth()->guest())
                        <h3 class="theme-color price">
                            ${{moneyFormat($item->msrp)}}{{$item->type == 'services' ? "/mo" : null}}
                            @if($item->track_qty)
                                <span class="text-content"
                                      style="font-size:12px;">QTY Available: {{$item->on_hand}}</span>
                            @endif
                        </h3>
                    @else
                        @if($item->type == 'services')

                            <h3 class="theme-color price">
                                ${{moneyFormat($item->mrc)}}/mo
                                @if($item->mrc < $item->msrp)
                                    <del class="text-content">${{moneyFormat($item->msrp)}}</del>
                                    <span class="offer theme-color">({{$item->perc}}% off)</span>
                                @endif
                                @if($item->track_qty)
                                    <span class="text-content"
                                          style="font-size:12px;">QTY Available: {{$item->on_hand}}</span>
                                @endif
                            </h3>
                        @else
                            <h3 class="theme-color price">
                                ${{moneyFormat($item->nrc)}}
                                @if($item->nrc < $item->msrp)
                                    <del class="text-content">${{moneyFormat($item->msrp)}}</del>
                                    <span class="offer theme-color">({{$item->perc}}% off)</span>
                                @endif
                                @if($item->track_qty)
                                    <span class="text-content"
                                          style="font-size:12px;">QTY Available: {{$item->on_hand}}</span>
                                @endif
                            </h3>
                        @endif
                    @endif


                </div>


                <div class="procuct-contain">
                    <p>{!! $item->description !!}
                        @if($item->full)
                            <br/><br/><a href='#' class="scroll-to" data-target="#description"><i
                                    class="fa fa-arrow-right"></i> more details</a>
                        @endif
                    </p>
                </div>
                @if($item->variation_category || ($item->parent && $item->parent->variation_category))
                    <div class="product-packege">
                        <div class="product-title">
                            <h4>{{$item->variationCategoryName}}</h4>
                        </div>
                        <ul class="select-packege">
                            @if($item->parent)
                                <li>
                                    <a href="#" wire:click="changeVariation({{$item->parent->id}})"
                                       class="{{$variationSelected == $item->parent->id ? "active" : null}}">{{$item->parent->variation_name}}</a>
                                </li>
                                @foreach($item->parent->children as $child)
                                    <li>
                                        <a href="#" wire:click="changeVariation({{$child->id}})"
                                           class="{{$variationSelected == $child->id ? "active" : null}}">{{$child->variation_name}}</a>
                                    </li>
                                @endforeach
                            @else
                                @foreach($item->children as $child)
                                    <li>
                                        <a href="#" wire:click="changeVariation({{$child->id}})"
                                           class="{{$variationSelected == $child->id ? "active" : null}}">{{$child->variation_name}}</a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                @endif

                @if($item->reservation_mode)
                    <div class="card mt-3">
                        <div class="card-header">
                            <h4 class="card-title mt-2 text-center">Reserve {{$item->name}} Now!</h4>
                        </div>
                        <div class="card-body">
                            <p>
                                {!! nl2br($item->reservation_details) !!}
                            </p>
                            <table class="table mt-3">
                                <tbody>

                                <tr>
                                    <td align="right" width="30%"><b>Reservation Time:</b></td>
                                    <td>{!! nl2br($item->reservation_time) !!}</td>
                                </tr>
                                <tr>
                                    <td align="right"><b>Refund Policy:</b></td>
                                    <td>{!! nl2br($item->reservation_refund) !!}</td>
                                </tr>
                                </tbody>
                            </table>

                        </div>
                        <div class="card-footer">
                            <a class="btn btn-md bg-dark cart-button text-white w-100" href="#"
                               wire:click="addItem">{{$addText}}</a>
                        </div>
                    </div>
                @endif

                @if(!$item->reservation_mode)

                    <div class="product-packege">
                        @foreach($item->addons as $addon)
                            <div class="product-title">
                                <h4>{{$addon->name}} - <small>{{$addon->description}}</small></h4>
                            </div>
                            <ul class="select-packege">
                                <li><select name="add_{{$addon}}" wire:model="addons.add_{{$addon->id}}"
                                            class="form-select">
                                        <option value="" selected>-- None --</option>
                                        @foreach($addon->options as $option)
                                            <option value="{{$option->id}}">{{$option->name}}
                                                (+{{moneyFormat($option->price)}})
                                            </option>
                                        @endforeach
                                    </select>
                            </ul>
                        @endforeach


                    </div>
                    <div class="note-box product-packege">
                        <div class="cart_qty qty-box product-qty">
                            <div class="input-group">
                                <button type="button" class="qty-left-minus" wire:click="decreaseQty" data-type="minus"
                                        data-field="">
                                    <i class="fa fa-minus" aria-hidden="true"></i>
                                </button>
                                <input class="form-control input-number qty-input" type="text" name="quantity"
                                       wire:model="qtySpinner">
                                <button type="button" class="qty-right-plus" wire:click="increaseQty" data-type="plus"
                                        data-field="">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>

                        <a class="btn btn-md bg-dark cart-button text-white w-100" href="#"
                           wire:click="addItem">{{$addText}}</a>
                    </div>
                @endif

                @if($item->feature_headline)
                    <div class="pickup-box mt-5">
                        <div class="product-title">
                            <h4>{{$item->name}} Highlights</h4>
                        </div>

                        <div class="pickup-detail">
                            <h4 class="text-content">{{$item->feature_headline}}</h4>
                        </div>

                        <div class="product-info">
                            <ul class="product-info-list product-info-list-2">
                                @foreach($item->feature_array as $feat)
                                    <li>{{$feat}}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif


            </div>


        </div>
    </div>

    <div class="row mt-3 mb-3">


        <div class="col-xxl-12">

            <div class="product-section-box m-0">
                <ul class="nav nav-tabs custom-nav" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="description-tab" data-bs-toggle="tab"
                                data-bs-target="#description" type="button" role="tab" aria-controls="description"
                                aria-selected="true">Description
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="info-tab" data-bs-toggle="tab" data-bs-target="#faq" type="button"
                                role="tab" aria-controls="info" aria-selected="false">Frequently Asked Questions
                        </button>
                    </li>


                </ul>


                <div class="tab-content custom-tab">
                    <div class="tab-pane fade show active" id="description" role="tabpanel">
                        {!! $item->full !!}
                    </div>

                    <div class="tab-pane fade" id="faq" role="tabpanel">
                        @include('shop.category.item.faq', ['item' => $item])
                    </div>
                </div>


            </div>

        </div>

    </div>

</div>

