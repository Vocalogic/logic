<p class="mb-3">
    Enter the name of the new quote. This quote will be created as a draft and you will be
    prompted to enter services once saved.
</p>
<div class="card border-primary">
    <div class="card-body">
        <form method="post" action="/admin/quotes">
            @method('POST')
            @csrf
            @if($obj instanceof \App\Models\Account)
                @props(['val' => $obj->name . " Quote ". now()->format("m/d"),
                    'expires' => now()->addDays((int)setting('quotes.length'))->format("Y-m-d")
                ])
                <x-form-input name="name" :value="$val"
                              label="Quote Name"
                              placeholder="Sample Quote Name"
                              icon="sliders">
                    Enter the name for the quote.
                </x-form-input>

                <x-form-input type="date" name="expires_on" :value="$expires" label="Quote Expires On"
                              icon="calendar-o">
                    Enter expiration for Quote
                </x-form-input>
                <x-form-input name="net_terms" value="{{setting('invoices.net')}}" label="Payment NET Days"
                              icon="calendar">
                    Enter the NET days for payment after acceptance.
                </x-form-input>

                <input type="hidden" name="account_id" value="{{$obj->id}}">
            @else

                @props(['val' => $obj->company . " Quote ". now()->format("m/d"),
                'expires' => now()->addDays((int)setting('quotes.length'))->format("Y-m-d")
                ])
                <x-form-input name="name" :value="$val"
                              label="Quote Name"
                              placeholder="Sample Quote Name"
                              icon="sliders">
                    Enter the name for the quote.
                </x-form-input>
                <x-form-input type="date" name="expires_on" :value="$expires" label="Quote Expires On"
                              icon="calendar-o">
                    Enter expiration for Quote
                </x-form-input>
                <x-form-input name="net_terms" value="{{setting('invoices.net')}}" label="Payment NET Days"
                              icon="calendar">
                    Enter the NET days for payment after acceptance.
                </x-form-input>
                <input type="hidden" name="lead_id" value="{{$obj->id}}">
            @endif


            <div class="row mt-2">
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary ladda pull-right"
                            data-style="zoom-out">
                        <i class="fa fa-plus"></i> Create Quote
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
