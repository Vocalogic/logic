INVOICE TOTAL: ${{moneyFormat($invoice->total)}}
BALANCE: ${{moneyFormat($invoice->balance)}}
------------------------
@foreach($invoice->items as $item)
{{$item->qty}} x {{$item->item ? "[{$item->item->code}] {$item->item->name}" : $item->name}} (${{moneyFormat($item->price * $item->qty)}})
@endforeach

