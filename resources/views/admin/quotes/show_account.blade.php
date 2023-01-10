<div class="row">
    <div class="col-lg-12">
        @if($quote->coterm_id)
            <div class="alert border-info">
                <i class="fa fa-exclamation-circle"></i> This quote will terminate contract from <strong>Quote #{{$quote->coterm->id}} ({{$quote->coterm->name}})</strong> and
                if executed will create a co-termed contract terminating on <strong>{{$quote->coterm->contract_expires->format("m/d/y")}}</strong> if not renewed.
                @if($quote->items->count() == 0)
                <br/><Br/>You can <a href="/admin/quotes/{{$quote->id}}/import/{{$quote->coterm->id}}"><i class="fa fa-refresh"></i> import items from Quote #{{$quote->coterm->id}}</a> to get started.
                @endif
            </div>
        @endif
        @include('admin.quotes.builder_account')
    </div>
</div>
@include('admin.quotes.decline_modal')
