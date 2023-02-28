<p>
    Updating pricing here will automatically assign new products and services on an account or quote
    to the pricing set here.
</p>
<form method="POST" action="/admin/accounts/{{$account->id}}/pricing/update/{{$item->id}}">
    @csrf
    @method('POST')
    <x-form-input name="price" label="{{$account->name}} Pricing" icon="money" value="{{moneyFormat($item->price)}}">
        Enter the price for {{$account->name}}.
    </x-form-input>
    @if($account->children())
        <x-form-input name="price_children" label="{{$account->name}} Sub-Account Pricing" icon="money" value="{{moneyFormat($item->price_children)}}">
            Enter the price for all accounts that are under {{$account->name}}.
        </x-form-input>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <button type="submit" name="submit" class="btn btn-sm btn-primary ladda pull-right" data-style="expand-left">
                <i class="fa fa-save"></i> Update Pricing
            </button>
        </div>
    </div>

</form>
