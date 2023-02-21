<div class="sModalArea">
    <form method="POST" action="/admin/accounts/{{$item->account->id}}/services/{{$item->id}}">
        @method("PUT")
        @csrf

        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex justify-content-evenly">
                    @if($item->quote)
                        <a href="/admin/quotes/{{$item->quote->id}}">
                            <span class="badge bg-info">Sold via quote #{{$item->quote->id}}</span>
                        </a>
                    @endif

                    @if($item->quote && $item->quote->contract_expires)
                        <span class="badge bg-primary">
                            contracted until {{$item->quote->contract_expires->format('m/d/y')}}
                        </span>
                    @endif

                    @if($item->frequency != \App\Enums\Core\BillFrequency::Monthly && $item->frequency)
                        <span class="badge bg-info">
                            {{$item->frequency->getHuman()}} Billing (Bills:
                            {{$item->next_bill_date
                                ? $item->next_bill_date->format("m/d/y")
                                : $account->next_bill->format("m/d/y")}})
                        </span>
                    @endif

                    @if($item->remaining)
                        <span class="badge bg-primary">
                                {{$item->remaining}} payments left
                            </span>
                    @endif
                    @if($item->terminate_on)
                        <span class="badge bg-danger">
                                Terminating on {{$item->terminate_on->format("m/d/y")}} - {{$item->terminate_reason}}
                            </span>
                    @endif
                    @if($item->suspend_on)
                        <span class="badge bg-warning">
                                Suspending on {{$item->suspend_on->format("m/d/y")}} - {{$item->suspend_reason}}
                            </span>
                    @endif

                    @if($item->requested_termination_date)
                        <span class="badge bg-warning">Customer Requested Termination on
                                {{$item->requested_termination_date->format("m/d/y")}} - {{$item->requested_termination_reason}}
                            </span>
                    @endif

                </div>
            </div>
        </div>


        <ul class="nav nav-tabs tab-card" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#nav-pricing"
                                    role="tab">Pricing</a></li>

            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#nav-notes" role="tab">Notes</a></li>
            @if($item->account->hasContract)
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#contract-nav"
                                        role="tab">Contract</a>
                </li>
            @endif
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#actions" role="tab">Actions</a></li>
        </ul>

        <div class="tab-content mt-4 mb-3">

            <div class="tab-pane fade show active" id="nav-pricing" role="tabpanel">


                <div class="row g-2">
                    <div class="col-md-4 col-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="price"
                                   value="{{moneyFormat($item->price)}}">
                            <label>Price</label>
                            <span class="helper-text">Update Monthly Price.</span>
                        </div>
                    </div>
                    <div class="col-md-4 col-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="qty" value="{{$item->qty}}">
                            <label>QTY</label>
                            <span class="helper-text">Update Quantity.</span>
                        </div>
                    </div>
                    <div class="col-md-4 col-4">
                        <div class="form-floating">
                            {!! Form::select('frequency', \App\Enums\Core\BillFrequency::getSelectable(), $item->frequency?->value ?: 'MONTHLY', ['class' => 'form-control']) !!}
                            <label>Billing Frequency</label>
                            <span class="helper-text">Select how often this service will be billed.</span>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <div class="form-floating">
                            <textarea class="form-control" name="description"
                                      style="height:150px;">{{$item->description}}</textarea>
                            <label>Description</label>
                            <span class="helper-text">Update description of item.</span>
                        </div>
                    </div>
                </div>
            </div>


            <div class="tab-pane fade" id="nav-notes" role="tabpanel">
                <div class="row mt-2">
                    <div class="col-md-12">
                        <div class="form-floating">
                            <textarea class="form-control" name="notes"
                                      style="height: 150px;">{{$item->notes}}</textarea>
                            <label>Notes</label>
                            <span class="helper-text">Enter additional notes on service (i.e. specifics)</span>
                        </div>
                    </div>
                </div>
            </div>
            @if($item->account->hasContract)
                <div class="tab-pane fade" id="contract-nav" role="tabpanel">
                    @include('admin.accounts.services.contract_modal', ['item' => $item])
                </div>
            @endif

            <div class="tab-pane fade" id="actions" role="tabpanel">

                <div class="list-group my-2">



                    <span class="list-group-item">

                    <a href="/admin/accounts/{{$item->account->id}}/items/{{$item->id}}/suspend"
                       class="confirm"
                       data-method="POST"
                       data-message="Send Immediate Service Suspension Notice?">
                        <i class="fa fa-clock-o"></i> Send Suspension Notice
                    </a>
                    </span>

                    <span class="list-group-item">
                         <a href="/admin/accounts/{{$item->account->id}}/items/{{$item->id}}/terminate"
                            class="confirm"
                            data-method="POST"
                            data-message="Send Immediate Termination Notice? This will NOT remove the service. You will need to remove the service manually.">
                    <i class="fa fa-times"></i> Send Termination Notice
                </a>

                    </span>

                    @if($item->terminate_on)
                        <span class="list-group-item">
                            <a class="confirm"
                               data-method="POST"
                               data-message="Are you sure you want to clear the termination date?"
                               href="/admin/accounts/{{$item->account->id}}/items/{{$item->id}}/remove/termination"> Remove Termination
                            Date</a>
                        </span>
                    @endif

                    @if($item->suspend_on)
                        <span class="list-group-item">

                        <a class="confirm"
                           data-method="POST"
                           data-message="Are you sure you want to clear the suspension date?"
                           href="/admin/accounts/{{$item->account->id}}/items/{{$item->id}}/remove/suspension"> Remove Suspension
                            Date</a>
                        </span>
                    @endif


                </div>
            </div>

            <div class="col-lg-12 mt-3">
                <div class="d-flex justify-content-between">

                    <a href="/admin/accounts/{{$item->account->id}}/services/{{$item->id}}"
                       class="confirm text-danger"
                       data-method="DELETE"
                       data-message="Are you sure you want to remove this service?">
                        <i class="fa fa-trash"></i> Remove Service
                    </a>

                    <button type="submit" class="btn btn-primary ladda" data-style="zoom-out">
                        <i class="fa fa-save"></i> Update Service
                    </button>
                </div>
            </div>
        </div>
    </form>


</div>
