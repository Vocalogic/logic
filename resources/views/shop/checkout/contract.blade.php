@php
    $terms = explode(",",setting('quotes.terms'));
    $cart = cart();
@endphp
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Contract Term</h4>
        <p class="mt-3">
            {{setting('brand.name')}} provides you the ability to receive discounts on certain product and services
            should you decide to enroll in a contract term. The following below shows discounts received based
            on the term you select.
        </p>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Item</th>
                <th>Price Selected</th>
                <th>No Contract</th>
                @foreach($terms as $term)
                    <th>{{$term}} Months</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($cart->items as $item)
                <tr>
                    <td>{{$item->name}} x {{$item->qty}}</td>
                    <td>${{moneyFormat($item->price * $item->qty)}}</td>
                    <td>${{moneyFormat($item->msrp * $item->qty)}}</td>
                    @foreach($terms as $term)
                        <td>
                            ${{ moneyFormat(\App\Models\BillItem::find($item->id)->discountTermValue($item->msrp * $item->qty, $term))}}</td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">


                <div class="form-floating theme-form-floating">
                    <select class="form-select" id="contractTerm" wire:model="contractTerm"
                            wire:change="updateContractTerm" aria-label="Select Contract Term">
                        <option value="0">Month-to-Month (No contract)</option>
                        @foreach($terms as $term)
                            <option value="{{$term}}">{{$term}} Months</option>
                        @endforeach
                    </select>
                    <label for="floatingSelect">Select Contract Term</label>
                </div>
            </div>
        </div>


        <div class="row mt-2">
            <button class="btn text-white bg-success" wire:click="moveForward"><i class="fa fa-floppy-disk"></i> &nbsp;
                Save Contract Selection
            </button>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">


                <h4 class="card-title">Selecting a Contract Term</h4>
                <p class="mt-2"><b>Note:</b> By selecting a contract term other than month to month, you will be
                    required to sign
                    the {{setting('brand.name')}} Master Services Agreement after verifying your order in the last step.
                </p>

                <div class="row mt-4">
                    <div class="col-lg-6">
                        <h3>Monthly Services Total: ${{moneyFormat(cart()->totalMonthly)}}</h3>
                    </div>
                    <div class="col-lg-6">
                        <h3>One-Time Purchase Total: ${{moneyFormat(cart()->totalOne)}}</h3>
                    </div>
                </div>


            </div>
        </div>

    </div>

</div>
