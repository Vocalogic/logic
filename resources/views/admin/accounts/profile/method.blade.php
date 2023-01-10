<div class="card mb-2 border-{{$account->merchant_payment_token ? "success" : "danger"}}">
    <div class="card-body">
        <h4>Payment Method</h4>
        <p>
            You currently have a merchant integration active. Use the form below to
            update the credit card method on file.
        </p>

        @if($account->merchant_payment_token)
            <div class="alert {{currentMode() == 'dark' ? 'bg-light-success' : 'alert-success'}}">{{$account->name}} has a {{$account->merchant_payment_type  ?: "Encrypted Card"}}
                ending in x{{$account->merchant_payment_last4 ?: "Encrypted"}} on file. You can change below if necessary.
            </div>
        @endif

        @if(getIntegration(\App\Enums\Core\IntegrationType::Merchant) == \App\Enums\Core\IntegrationRegistry::LogicPay)
            @include('admin.accounts.profile.merchant_logic')
            @if(getIntegration(\App\Enums\Core\IntegrationType::Merchant)->connect()->achEnabled())
                <a class="live btn btn-primary mt-3" data-title="Update ACH Information"
                   href="/admin/accounts/{{$account->id}}/updateACH">Update ACH Details</a>
            @endif

        @endif

        @if(getIntegration(\App\Enums\Core\IntegrationType::Merchant) == \App\Enums\Core\IntegrationRegistry::Stripe)
            @include('admin.accounts.profile.merchant_stripe')
        @endif


        <a class="mt-3 confirm btn btn-{{bm()}}primary"
           data-method="GET"
           data-message="This will send {{$account->name}} a request to re-add their credit card. Are you sure you want to do this?"
           href="/admin/accounts/{{$account->id}}/paymentRequest"><i class="fa fa-recycle"></i> Request new Credit Card</a>

    </div>
</div>
