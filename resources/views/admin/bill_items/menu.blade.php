<a class="btn btn-outline-info w-100 btn-block live mt-3"
   data-title="Create new {{\Illuminate\Support\Str::singular($item->type)}}"
   href="/admin/category/{{$cat->id}}/items/create">
    <i class="fa fa-plus"></i> Add New Item
</a>


@if(!$item->parent)
    <a class="live mt-2 w-100 btn btn-{{bm()}}primary" data-title="Variation to {{$item->name}}"
       href="/admin/category/{{$item->category->id}}/items/{{$item->id}}/variation/create">
        <i class="fa fa-recycle"></i> Add Variation
    </a>

@endif

<a class="live mt-2 btn w-100 btn-{{bm()}}info" data-title="Move Category for {{$item->name}}"
   href="/admin/category/{{$item->category->id}}/items/{{$item->id}}/category">
    <i class="fa fa-arrow-right"></i> Change Category
</a>


<div class="card mt-4 mb-3">
    <div class="card-body">
        <h6 class="card-title mb-3 text-center">{{$item->name}}</h6>
        <ul class="list-group list-group-custom">
            <li class="list-group-item {{preg_match("/specs/", app('request')->getUri()) ? "active" : null}}">
                <a class="color-600" href="/admin/category/{{$cat->id}}/items/{{$item->id}}/specs">Definition</a>
            </li>
            <li class="list-group-item {{preg_match("/pricing/", app('request')->getUri()) ? "active" : null}}">
                <a class="color-600" href="/admin/category/{{$cat->id}}/items/{{$item->id}}/pricing">Pricing</a>
            </li>
            <li class="list-group-item {{preg_match("/photos/", app('request')->getUri()) ? "active" : null}}">
                <a class="color-600" href="/admin/category/{{$cat->id}}/items/{{$item->id}}/photos">Photos</a>
            </li>
            <li class="list-group-item {{preg_match("/addons/", app('request')->getUri()) ? "active" : null}}">
                <a class="color-600" href="/admin/category/{{$cat->id}}/items/{{$item->id}}/addons">Addons</a>
            </li>
            <li class="list-group-item {{preg_match("/tags/", app('request')->getUri()) ? "active" : null}}">
                <a class="color-600" href="/admin/category/{{$cat->id}}/items/{{$item->id}}/tags">Tags</a>
            </li>
            <li class="list-group-item {{preg_match("/faq/", app('request')->getUri()) ? "active" : null}}">
                <a class="color-600" href="/admin/category/{{$cat->id}}/items/{{$item->id}}/faq">FAQ</a>
            </li>
            <li class="list-group-item {{preg_match("/requirement/", app('request')->getUri()) ? "active" : null}}">
                <a class="color-600" href="/admin/category/{{$cat->id}}/items/{{$item->id}}/requirements">Data
                    Tracking</a>
            </li>

            @if($item->type == 'products')
                <li class="list-group-item {{preg_match("/reservation/", app('request')->getUri()) ? "active" : null}}">
                    <a class="color-600"
                       href="/admin/category/{{$cat->id}}/items/{{$item->id}}/reservation">Reservation</a>
                </li>
            @endif
            <li class="list-group-item {{preg_match("/variation/", app('request')->getUri()) ? "active" : null}}">
                <a class="color-600" href="/admin/category/{{$cat->id}}/items/{{$item->id}}/variation">Variations</a>
            </li>
            <li class="list-group-item {{preg_match("/shop/", app('request')->getUri()) ? "active" : null}}">
                <a class="color-600" href="/admin/category/{{$cat->id}}/items/{{$item->id}}/shop">Shop Settings</a>
                @if(!$item->shop_show)
                    <span class="badge bg-warning">disabled</span>
                @endif
            </li>

        </ul>
    </div>
</div>

<a href="/admin/category/{{$cat->id}}/items/{{$item->id}}"
   class="btn btn-danger w-100 confirm mt-2"
   data-method="DELETE"
   data-message="Are you sure you want to remove this item?">
    <i class="fa fa-trash"></i> Remove
</a>
