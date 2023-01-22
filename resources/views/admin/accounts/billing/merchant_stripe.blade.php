@if(auth()->guest())
    <p>Click the button below to authorize your card for services.</p>

    {!! Form::open(['url' => "/payment/$account->cc_reset_hash", 'method' => 'POST']) !!}
    @elseif (user()->account_id == 1)
    {!! Form::open(['url' => "/admin/accounts/$account->id/method/add", 'method' => 'POST']) !!}
    @else
    {!! Form::open(['url' => "/shop/account/method", 'method' => 'POST']) !!}
@endif
<script
    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
    data-key="{{\App\Enums\Core\IntegrationRegistry::Stripe->connect()->config->stripe_publish}}"
    data-amount="100"
    data-name="{{setting('brand.name')}}"
    @if(!auth()->guest() && user()->account_id == 1)
        data-email="{{$account->admin->email}}"
    @else
    data-email="{{user()->account->admin->email}}"
    @endif
    data-description="Authorization Charge"
    data-label="Add/Update Payment Card"
    data-image="{{setting('brandImage.icon') ?: "https://stripe.com/img/documentation/checkout/marketplace.png"}}"
    data-locale="auto">
</script>
{!! Form::close() !!}
