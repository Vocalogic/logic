<div class="card">
    <div class="card-body">
        <h4>Auto-Pricing Helper</h4>

        <p>
            This feature is designed to help you price your products and services based on desired margins. To update
            these defaults you can <a href="/admin/settings/?tab=quote">edit the auto-pricing</a> parameters. The
            values below are based on
            @if(setting('quotes.pricingMethod') == 'Cost')
                an increase of <code>Item Cost</code>.
            @else
                a decrease from <code>MSRP</code>.
            @endif
            <b>Note:</b> This will not change any of the values to the left until you hit the save button in this
            editor.
        </p>
        @livewire('admin.pricing-component', ['item' => $item])
    </div>
</div>
