<table class="table table-striped mt-3">
    <thead>
    <tr>
        <th>{{$service ? "Service" : "Product"}}</th>
        <th>Catalog Price</th>
        <th>{{\Illuminate\Support\Str::limit($account->name, 10)}}</th>
        @if($account->children()->count())
            <th>Sub-Account Price</th>
        @endif
    </tr>
    </thead>
    <tbody>
    @if($service)
        @foreach($account->pricings()->whereRelation('item', 'type', 'services')->get() as $item)
            <tr>
                <td>[{{$item->item->code}}] {{$item->item->name}}
                    <a class="confirm text-danger" data-message="Are you sure you want to remove special pricing?"
                       data-method="DELETE"
                       href="/admin/accounts/{{$account->id}}/pricing/{{$item->id}}">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
                <td>${{moneyFormat($item->item->mrc)}}</td>
                <td>
                    $<a class="live" data-title="Update Pricing" href="/admin/accounts/{{$account->id}}/pricing/update/{{$item->id}}">
                        {{moneyFormat($item->price)}}
                    </a>
                </td>
                @if($account->children()->count())
                    <td>
                        $<a class="live"  data-title="Update Pricing" href="/admin/accounts/{{$account->id}}/pricing/update/{{$item->id}}/">
                            {{moneyFormat($item->price_children)}}
                        </a>
                    </td>
                @endif
            </tr>
        @endforeach
    @else

        @foreach($account->pricings()->whereRelation('item', 'type', 'products')->get() as $item)
            <tr>
                <td>[{{$item->item->code}}] {{$item->item->name}}
                    <a class="confirm text-danger" data-message="Are you sure you want to remove special pricing?"
                       data-method="DELETE"
                       href="/admin/accounts/{{$account->id}}/pricing/{{$item->id}}">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
                <td>${{moneyFormat($item->item->nrc)}}</td>
                <td>
                    $<a class="live" data-title="Update Pricing" href="/admin/accounts/{{$account->id}}/pricing/update/{{$item->id}}">
                        {{moneyFormat($item->price)}}
                    </a>
                </td>
                @if($account->children()->count())
                    <td>
                        $<a class="live" data-title="Update Pricing" href="/admin/accounts/{{$account->id}}/pricing/update/{{$item->id}}/">
                            {{moneyFormat($item->price_children)}}
                        </a>
                    </td>
                @endif
            </tr>
        @endforeach

    @endif
    </tbody>
</table>
