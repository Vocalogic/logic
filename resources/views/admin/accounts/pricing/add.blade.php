@if($type == 'service')
    <table class="table datatable">
        <thead>
        <tr>
            <th>Item</th>
            <th>Price</th>
        </tr>
        </thead>
        <tbody>
        @foreach(\App\Models\BillCategory::where('type', \App\Enums\Core\BillItemType::SERVICE)->get() as $cat)
            @foreach($cat->items as $item)
                @if($account->pricings()->where('bill_item_id', $item->id)->count())
                    @continue
                @endif
                <tr>
                    <td>
                        <a href="/admin/accounts/{{$account->id}}/pricing/{{$item->id}}">
                            [{{$item->code}}] {{$item->name}}</a><br/>
                        <small class="text-muted">{{$item->category->name}}</small>
                    </td>
                    <td>${{moneyFormat($item->mrc)}}</td>
                </tr>
            @endforeach
        @endforeach
        </tbody>
    </table>

    @else

    <table class="table datatable">
        <thead>
        <tr>
            <th>Item</th>
            <th>Price</th>
        </tr>
        </thead>
        <tbody>
        @foreach(\App\Models\BillCategory::where('type', \App\Enums\Core\BillItemType::PRODUCT)->get() as $cat)
            @foreach($cat->items as $item)
                @if($account->pricings()->where('bill_item_id', $item->id)->count())
                    @continue
                @endif
                <tr>
                    <td>
                        <a href="/admin/accounts/{{$account->id}}/pricing/{{$item->id}}">
                            [{{$item->code}}] {{$item->name}}</a><br/><small
                            class="text-muted">{{$item->category->name}}</small>
                    </td>
                    <td>${{moneyFormat($item->nrc)}}</td>
                </tr>
            @endforeach
        @endforeach
        </tbody>
    </table>
    @endif

<script>
    $('.datatable').dataTable();
</script>
