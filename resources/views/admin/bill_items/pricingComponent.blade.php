<div>
    @if($locked)
        <div class="alert alert-primary alert-outline" role="alert">Pricing Helper is currently disabled. <a href="#" wire:click="unlock">Click here</a> to
            unlock and override pricing based on recommended metrics.
        </div>
    @else
        <form>
            <div class="row">
                <div class="col-lg-6">

                    <div class="row">


                        <div class="col-lg-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" wire:model="variance">
                                <label>Base Variance Percentage</label>
                                <span class="helper-text">Enter the percentage to define base selling price.</span>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" wire:model="saleVariance">
                                <label>Min/Max Variance from Base</label>
                                <span class="helper-text">Enter the percentage to define min/max selling prices from base sale price.</span>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <input type="submit" class="btn btn-primary w-100 mt-3" wire:click="apply"
                                   value="Apply Pricing">
                        </div>


                    </div>


                </div>


                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Recommended Pricing</h6>

                            <div class="list-group my-2">
                                <label class="list-group-item d-flex align-items-center">
                                    <span class="flex-grow-1">Selling Price</span>
                                    <span class="badge bg-light">${{moneyFormat($sellingPrice)}}</span>
                                </label>
                                <label class="list-group-item d-flex align-items-center">
                                    <span class="flex-grow-1">Min Allowed Price</span>
                                    <span class="badge bg-light">${{moneyFormat($minAllowed)}}</span>
                                </label>
                                <label class="list-group-item d-flex align-items-center">
                                    <span class="flex-grow-1">Max Allowed Price</span>
                                    <span class="badge bg-light">${{moneyFormat($maxAllowed)}}</span>
                                </label>

                                <label class="list-group-item d-flex align-items-center">
                                    <span class="flex-grow-1">Profit Amount</span>
                                    <span class="badge bg-light">${{moneyFormat($profitAmount)}}</span>
                                </label>

                                <label class="list-group-item d-flex align-items-center">
                                    <span class="flex-grow-1">Margin Percentage</span>
                                    <span
                                        class="badge bg-light">{{number_format($profitPercentage,2)}}%</span>
                                </label>

                            </div>

                        </div>
                    </div>


                </div>


            </div>
        </form>
    @endif
</div>
