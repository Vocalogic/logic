@livewire('shop.cart-icon-component', ['mini' => false])

<div class="right-sidebar-box">
    <div class="right-box-contain">



        <div class="pickup-box">

            <div class="product-title">
                <h4>Additional Information</h4>
            </div>

            <div class="product-info">
                <ul class="product-info-list product-info-list-2">
                    <li><strong>SKU:</strong> {{$item->code}}</li>
                    @foreach($item->tags as $tag)
                        <li><strong>{{$tag->tag->category->name}}: </strong> {{$tag->tag->name}}</li>
                    @endforeach
                    @if($item->track_qty)
                        <li><strong>Stock: </strong> {{$item->on_hand}} Left</li>
                    @endif
                </ul>
            </div>
        </div>

    </div>
</div>


