<div>
    <div class="row">
        <div class="col-xxl-4">
            @livewire('shop.download-quote-component')
        </div>

        <div class="col-xxl-8">
            <div class="blog-details-quote">
                <h3>Creating a Quote from your Cart</h3>
                <p class="mt-3">When creating a quote from your cart a valid email address is required. You will
                    also be required to validate your address via <b>{{setting('shop.verification')}}</b>
                    before your quote will be emailed or available for download.

                </p>
                <div class="alert alert-info">Do not navigate away from this page before verification is complete, or you
                will need to restart the verification process.</div>

                <div class="mt-3">
                    @livewire('verification-component')
                </div>
            </div>
        </div>
    </div>
</div>
