<div>
    <section>
        <div class="container-fluid-lg">
            @if($category->shop_offer)
                @include('shop.category.slide')
            @endif
        </div>
    </section>

    <section class="section-b-space shop-section">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-xxl-3 col-lg-4">
                    @include('shop.category.sidelist')
                </div>
                <div class="col-xxl-9 col-lg-8">
                    @include('shop.category.items')
                </div>
            </div>
        </div>
    </section>
</div>
