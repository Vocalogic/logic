@forelse($item->faqs as $faq)
    <b class="fs-6">{{$faq->question}}</b>
    <br/>
    {!! $faq->answer !!}<br/><Br/>
@empty
    <b>There are no questions about this product.</b>
@endforelse
