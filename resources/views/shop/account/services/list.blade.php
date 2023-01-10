<div class="row row-deck">
    @foreach($account->items as $item)

        <div class="col-xxl-3 col-lg-6 col-md-4 col-sm-6">

            <div class="card">
                @if($item->item->photo_id)
                    <img src="{{_file($item->item->photo_id)->relative}}"
                         class="card-img-top img-thumbnail img-fluid" alt="" style="max-height: 200px; display:block;">
                @endif

                <div class="card-body">

                    <h5 class="card-title">{{$item->item->name}}</h5>
                    <p class="card-text">
                        {{$item->description}}
                        @if($item->notes)
                            <br/><br/> <strong class="theme-color">{!! nl2br($item->notes) !!}</strong>
                        @endif
                    </p>
                    <a class="live" data-title="Request Termination" href="/shop/account/services/{{$item->id}}/term">
                        @if($item->terminate_on)
                            <span
                                class="badge bg-danger">Terminating on: {{$item->terminate_on->format("m/d/y")}}</span>
                        @elseif($item->suspend_on)
                            <span class="badge bg-warning">Suspending on: {{$item->suspend_on->format("m/d/y")}}</span>
                        @elseif($item->requested_termination_date)
                            <span class="badge bg-warning">Termination Requested for:
                                {{$item->requested_termination_date->format("m/d/y")}}
                            </span>
                        @else
                            <span class="badge bg-info"><i class="fa fa-clock"></i> Request Cancellation</span>
                        @endif
                    </a>
                </div>
                <div class="card-footer text-center">
                      <span class="theme-color">${{moneyFormat($item->price)}} x {{$item->qty}}
                          (<b>${{moneyFormat($item->price * $item->qty)}}/mo</b>)
                      </span>
                </div>

            </div>

        </div>
    @endforeach

</div>

