<div>
    @if($message)
        <div class="row">
            <div class="col-lg-6">
                <div class="alert {{bma()}}success">
                    {{$message}}
                </div>
            </div>
        </div>

    @endif

    <div class="row">
        <div class="col-lg-6">
            <div class="form-floating">
                <input type="text" class="form-control" name="productName" wire:model="productName">
                <label>Product Name</label>
                <span class="helper-text">Enter the product name to ask OpenAI</span>
            </div>
        </div>
        <div class="col-lg-6">
            <div wire:loading>
                <div class="alert alert-info">
                    <i class="fa fa-spin fa-refresh"></i> Connected to OpenAI.
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <textarea wire:model="quoteDescription" class="form-control" style="height:200px;">
            </textarea>
            <a class="btn btn-sm btn-primary mb-3" href="#" wire:click="generateInvoiceLine">Create Product Description
                for
                Invoice</a>

            <a class="btn btn-sm btn-success mb-3" href="#" wire:click="saveInvoiceLine">Use this Description</a>


            <textarea wire:model="faq" class="form-control" style="height:200px;">
            </textarea>
            <a class="btn btn-sm btn-primary mb-3" href="#" wire:click="generateFaq">Generate Frequently Asked Questions
            </a>
            <a class="btn btn-sm btn-success mb-3" href="#" wire:click="saveFaq">Use these FAQ</a>


        </div>

        <div class="col-lg-6">
            <textarea wire:model="marketing" class="form-control" style="height:500px;">
            </textarea>
            <a class="btn btn-sm btn-primary mb-3" href="#" wire:click="generateMarketing">Create Product Marketing for
                Shop</a>
            <a class="btn btn-sm btn-success mb-3" href="#" wire:click="saveMarketing">Use this Marketing
                Description</a>
        </div>
    </div>

</div>
