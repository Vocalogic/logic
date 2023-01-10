<div class="rightbar card">

    <div class="card-header bg-transparent">
        <h6 class="card-title btn-right mb-0">
            {{$item->name}} Preview
        </h6>
    </div>


    <div class="card ribbon overflow-hidden mb-3 mt-5">
        <div class="option-7 bg-info position-absolute text-light">${{moneyFormat($item->nrc)}}</div>
        @if($item->photo_id)
            <img class="card-img-top" src="{{_file($item->photo_id)->relative}}" alt="{{$item->name}} Photo">
        @endif
        <div class="card-body">
            <h5 class="mb-4">{{$item->name}}</h5>
            <p class="lead">{!! nl2br($item->description)!!}</p>

            <h6 class="card-title mb-3">{{$item->feature_headline}}</h6>
            <ul class="list-group list-group-custom">
                @foreach($item->featureArray as $i)
                    <li class="list-group-item"><a class="color-600" href="#">{{$i}}</a></li>
                @endforeach

            </ul>
        </div>

    </div>


    <div class="card">
        <div class="card-body d-flex align-items-center p-4">
            <div class="avatar lg rounded-circle no-thumbnail"><i class="fa fa-credit-card fa-lg"></i></div>
            <div class="flex-fill ms-3 text-truncate">
                <div class="text-muted">Profit</div>
                <h5 class="mb-0">${{moneyFormat($item->profit)}}</h5>
            </div>
        </div>
    </div>


</div>
