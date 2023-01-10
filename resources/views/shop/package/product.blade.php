@php
    $options = \App\Models\PackageSectionQuestionOption::where('package_section_question_id', $q['id'])->get();
    $tags = [];
    foreach($options as $opt)
        {
            $tags[] = $opt->option;
        }
    $tagged = \App\Models\BillItemTag::with('item')->whereIn('tag_id', $tags)->get();
    $items = [];
    foreach($tagged as $tag)
        {
            if (!in_array($tag->bill_item_id, $items))
                {
                    $items[] = $tag->bill_item_id;
                }
        }
    $items = \App\Models\BillItem::whereIn('id', $items)->get();
@endphp



<div class="cart-table">
    <div class="table-responsive-xl">
        <table class="table">
            <tbody>
                @foreach($items as $item)
                    <tr class="product-box-contain">
                        <td class="product-detail">
                            <div class="product border-0">
                                <a href="/shop/{{$item->category->slug}}/{{$item->slug}}" class="product-image">
                                    @if($item->photo_id && _file($item->photo_id)?->relative)
                                        <img src="{{_file($item->photo_id)->relative}}"
                                             class="img-fluid blur-up lazyload" alt="{{$item->name}}">
                                    @endif
                                </a>
                                <div class="product-detail">
                                    <ul>
                                        <li class="name">
                                            <a href="/shop/{{$item->category->slug}}/{{$item->slug}}">{{$item->name}}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="mt-2">
                                {{$item->description}}
                                @if($item->notes)
                                    <br/><Br/><strong>{{$item->notes}}</strong>
                                @endif

                            </div>
                        </td>

                        <td class="price">
                            <h4 class="table-title text-content">Price</h4>
                            <h5>${{moneyFormat($item->msrp)}}</h5>

                        </td>

                        <td class="quantity">
                            <h4 class="table-title text-content">Qty</h4>
                            <div class="quantity-price">
                                        <div class="input-group">

                                            <input class="form-control" type="text"
                                                   name="quantity" wire:model="answers.q_{{$q['id']}}.i_{{$item->id}}">

                                        </div>


                            </div>
                        </td>



            </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
