<div class="card mt-2">

    <table class="table align-middle">
        <colgroup>
            <col class="col-md-1">
            <col class="col-md-2">
            <col class="col-md-8">
            <col class="col-md-2">
        </colgroup>
        <thead>
        <tr>
            <td></td>
            <td>{{ucfirst(\Illuminate\Support\Str::singular($cat->type))}}</td>
            <td>Description</td>
            <td>Default Price</td>
        </tr>
        </thead>
        <tbody>
        @foreach($cat->items()->whereNull('parent_id')->get() as $item)
            <tr>
                <td>@if($item->photo_id)
                        <a href="/admin/category/{{$item->bill_category_id}}/items/{{$item->id}}"> <img
                                src="{{_file($item->photo_id)?->relative}}" width="80"></a>
                    @endif
                </td>
                <td><a href="/admin/category/{{$item->bill_category_id}}/items/{{$item->id}}"
                       class="text-secondary">{{$item->name}}</span></a> @csrf
                @if($item->children()->count())
                        <a href="#" data-bs-toggle="popover" data-bs-html="true"
                           data-bs-title="{{$item->name}} Variations"
                           data-bs-content="{!! $item->variantExport !!}"><span class="badge bg-{{bm()}}primary">+{{$item->children()->count()}}</span></a>
                @endif
                </td>
                <td>{{$item->description}}</a>
                    <br/>
                    @foreach($item->tags as $tag)
                        <badge class="badge bg-{{bm()}}primary">{{$tag->tag->category->name}}: {{$tag->tag->name}}</badge>
                    @endforeach
                    @if($item->is_shipped)
                        <badge class="badge bg-{{bm()}}warning">Item is Shipped</badge>
                    @endif
                    @if($item->shop_show)
                        <a target="_blank" href="/shop/{{$item->category->slug}}/{{$item->slug}}"><badge class="badge bg-{{bm()}}info"><i class="fa fa-arrow-right"></i> View in Shop</badge></a>
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
