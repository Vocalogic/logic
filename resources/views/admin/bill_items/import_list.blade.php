@foreach($products as $cate)
    @if(app('request')->cat && $cate->category != app('request')->cat)
        @continue
    @endif
    @foreach($cate->products as $item)
        <div class="col-lg-4">


            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center my-4">
                        @if($item->photo)
                            <img class="avatar xl" src="data:image/png;base64,{{$item->photo}}" alt="{{$item->name}}">
                        @endif

                        <div class="flex-fill ms-3">
                            <div class="h5 mb-1">{{$item->name}}</div>
                            <span class="text-muted">{{substr($item->description,0,100)}}..</span>
                        </div>
                    </div>
                    <div class="d-flex">
                        <a href="#item-{{$item->id}}" class="btn mx-1 btn-light-primary flex-grow-1" data-bs-toggle="collapse"><i class="fa fa-plus me-2"></i>More Info</a>
                        <a href="/admin/category/{{$cat->id}}/items/import/{{$item->id}}" class="btn mx-1 btn-light-success flex-grow-1"><i class="fa fa-indent me-2"></i>Import</a>
                    </div>


                    <div class="collapse" id="item-{{$item->id}}"
                        <br/><br/>
                        <p>{!! nl2br($item->description)!!}</p>

                        <h6 class="card-title mt-2 mb-3">{{$item->headline}}</h6>
                        <ul class="list-group list-group-custom">
                            @php
                                $list = explode("\n", $item->list);
                            @endphp
                            @foreach($list as $i)
                                <li class="list-group-item"><a class="color-600" href="#">{{$i}}</a></li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>

        </div>



    @endforeach
@endforeach
