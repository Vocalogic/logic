<div class="card border card-border-@if($quote->activated_on)success @elseif($quote->presentable)primary @else danger @endif    ">
    <div class="card-body">
        <div class="d-flex">
            <h6 class="flex-grow-1">Quote #{{$quote->id}}</h6>
            <span class="badge d-inline-flex align-items-center
                    justify-content-start">
                        {{$quote->status}}
                    </span>
        </div>
        <h5 class="font-weight-bold"><b>${{moneyFormat($quote->total)}}</b></h5>
        <h6 class="text-muted">
            @if($quote->isPastExpiry && !$quote->activated_on)
                <span class="text-danger">Expired {{$quote->expires_on?->diffInDays()}} days ago</span>
            @elseif(!$quote->activated_on)
                Expires in {{$quote->expires_on?->diffInDays()}} days
            @endif

            @if($quote->activated_on)
                    <span class="text-success">Activated {{$quote->activated_on?->diffInDays()}} days ago</span>
            @endif
        </h6>
    </div>
    <div class="card-footer text-center p-2 bg-gray">
        <a class="text-info" href="/admin/quotes/{{$quote->id}}">
            View Quote #{{$quote->id}} <i class="fa fa-chevron-right"></i>
        </a>
    </div>
</div>
