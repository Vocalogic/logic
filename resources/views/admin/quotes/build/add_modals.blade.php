<div class="modal fade" id="newRecurring" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="px-xl-4 modal-header">
                <h5 class="modal-title">Add Recurring Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom_scroll">

                <table class="table datatable">
                    <thead>
                    <tr>
                        <th>Item</th>
                        <th>Price</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(\App\Models\BillCategory::with('items')->where('type', \App\Enums\Core\BillItemType::SERVICE)->get() as $cat)

                        @foreach($cat->items as $item)
                            <tr>
                                <td>
                                    <a href="/admin/quotes/{{$quote->id}}/add/{{$item->id}}">
                                        [{{$item->code}}] {{$item->name}}</a><br/><small
                                        class="text-muted">{{$cat->name}}</small>
                                </td>
                                <td>${{moneyFormat($item->mrc)}}</td>
                            </tr>
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="oneModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="px-xl-4 modal-header">
                <h5 class="modal-title">Add One-Time Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom_scroll">

                <table class="table datatable">
                    <thead>
                    <tr>
                        <th>Item</th>
                        <th>Price</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(\App\Models\BillCategory::with('items')->where('type', \App\Enums\Core\BillItemType::PRODUCT)->get() as $cat)
                        @foreach($cat->items as $item)
                            <tr>
                                <td>
                                    <a href="/admin/quotes/{{$quote->id}}/add/{{$item->id}}">
                                        [{{$item->code}}] {{$item->name}}
                                    </a><br/><small class="text-muted">
                                        {{$cat->name}}
                                    </small>
                                </td>
                                <td>${{moneyFormat($item->nrc)}}</td>
                            </tr>
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="recurringCopy" tabindex="-1" role="dialog" aria-labelledby="liveModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Copy Recurring Items</h5>
            </div>
            <div class="modal-body">
                <p>
                    You can copy recurring items from a previously created quote for this lead. This will allow you
                    to make secondary quotes quickly if you need to change things like term pricing, other options, etc.
                </p>
                <form method="post" action="/admin/quotes/{{$quote->id}}/copy?type=recurring">
                    @method('POST')
                    @csrf
                    <div class="row g-3 mb-4">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-floating mb-2">
                                @if($quote->lead)
                                    {!! Form::select('quote_id', array_replace([0 => '-- Select Quote --'], $quote->lead->alternateQuotes($quote)), null, ['class' => 'form-select', 'aria-label' => 'Select Quote']) !!}
                                @else
                                    {!! Form::select('quote_id', array_replace([0 => '-- Select Quote --'], $quote->account->alternateQuotes($quote)), null, ['class' => 'form-select', 'aria-label' => 'Select Quote']) !!}
                                @endif
                                <label>Select Previous Quote</label>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 mt-3">
                            <input type="submit" class="btn btn-primary rounded" value="Save">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="oneCopy" tabindex="-1" role="dialog" aria-labelledby="liveModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Copy One-time Items</h5>
            </div>
            <div class="modal-body">
                <p>
                    You can copy one-time items from a previously created quote for this lead. This will allow you
                    to make secondary quotes quickly if you need to change things like term pricing, other options, etc.
                </p>
                <form method="post" action="/admin/quotes/{{$quote->id}}/copy?type=one">
                    @method('POST')
                    @csrf
                    <div class="row g-3 mb-4">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-floating mb-2">
                                @if($quote->lead)
                                    {!! Form::select('quote_id', array_replace([0 => '-- Select Quote --'], $quote->lead->alternateQuotes($quote)), null, ['class' => 'form-select', 'aria-label' => 'Select Quote']) !!}
                                @else
                                    {!! Form::select('quote_id', array_replace([0 => '-- Select Quote --'], $quote->account->alternateQuotes($quote)), null, ['class' => 'form-select', 'aria-label' => 'Select Quote']) !!}
                                @endif
                                <label>Select Previous Quote</label>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 mt-3">
                            <input type="submit" class="btn btn-primary rounded" value="Save">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
