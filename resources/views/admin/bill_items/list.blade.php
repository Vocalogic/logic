<div class="card mt-4">

    <table class="table align-middle">
        <colgroup>
            <col class="col-md-2">
            <col class="col-md-8">
            <col class="col-md-2">
        </colgroup>
        <thead>
        <tr>
            <th>{{ucfirst(\Illuminate\Support\Str::singular($cat->type))}}</th>
            <th>Description</th>
            <th>Default Price</th>
        </tr>
        </thead>
        <tbody>
        @foreach($cat->items()->whereNull('parent_id')->get() as $item)
            <tr>

                <td>

                    @if($item->photo_id)
                        <a href="/admin/category/{{$item->bill_category_id}}/items/{{$item->id}}">
                            <figure>
                                <img src="{{_file($item->photo_id)?->relative}}" class="rounded mx-auto d-block"
                                     width="80">
                                <figcaption>
                                    <span class="text-secondary small">{{$item->name}}</span>
                                </figcaption>
                            </figure>
                        </a>
                    @else
                        <a href="/admin/category/{{$item->bill_category_id}}/items/{{$item->id}}">
                            <span class="text-secondary small">{{$item->name}}</span>
                        </a>
                    @endif
                </td>
                @if($item->children()->count())
                    <a href="#" data-bs-toggle="popover" data-bs-html="true"
                       data-bs-title="{{$item->name}} Variations"
                       data-bs-content="{!! $item->variantExport !!}"><span
                            class="badge bg-{{bm()}}primary">+{{$item->children()->count()}}</span></a>
                    @endif
                    </td>
                    <td>{{$item->description}}</a>
                        <br/>
                        @if($item->is_shipped)
                            <badge class="badge bg-{{bm()}}warning">Item is Shipped</badge>
                        @endif
                        @if($item->shop_show)
                            <a target="_blank" href="/shop/{{$item->category->slug}}/{{$item->slug}}">
                                <badge class="badge bg-{{bm()}}info"><i class="fa fa-arrow-right"></i> View in Shop
                                </badge>
                            </a>
                        @endif
                        @if($item->reservation_mode)
                            <badge class="badge bg-{{bm()}}danger">Reserve Advertised</badge>
                        @endif

                    </td>
                    <td>
                        ${{moneyFormat($item->type == \App\Enums\Core\BillItemType::PRODUCT->value ? $item->nrc : $item->mrc)}}
                        @if($item->margin > 0)
                            <span class="badge bg-{{bm()}}success">{{$item->margin}}%</span>
                        @else
                            <span class="badge bg-{{bm()}}danger">{{$item->margin}}%</span>
                        @endif
                    </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
