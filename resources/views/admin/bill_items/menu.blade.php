<a class="btn btn-primary w-100 btn-block live mt-3"
   data-title="Create new {{\Illuminate\Support\Str::singular($item->type)}}"
   href="/admin/category/{{$cat->id}}/items/create">
    <i class="fa fa-plus"></i> Add New Item
</a>

<a class="live mt-3 mb-3 btn w-100 btn-secondary" data-title="Move Category for {{$item->name}}"
   href="/admin/category/{{$item->category->id}}/items/{{$item->id}}/category">
    <i class="fa fa-arrow-right"></i> Change Category
</a>

@if($item->photo_id)
    <div class="mt-4 mb-2">
        <img class="img-fluid" src="{{_file($item->photo_id)?->relative}}">
    </div>
@endif


<div class="card mb-3">
    <div class="card-body">
        <h6 class=" mb-3 text-center">{{$item->name}}</h6>
        <div class="list-group">
            <a class="list-group-item {{preg_match("/specs/", app('request')->getUri()) ? "active" : null}}" href="/admin/category/{{$cat->id}}/items/{{$item->id}}/specs">Definition</a>
            <a class="list-group-item {{preg_match("/pricing/", app('request')->getUri()) ? "active" : null}}" href="/admin/category/{{$cat->id}}/items/{{$item->id}}/pricing">Pricing</a>
            <a class="list-group-item {{preg_match("/photos/", app('request')->getUri()) ? "active" : null}}" href="/admin/category/{{$cat->id}}/items/{{$item->id}}/photos">Photos</a>

            <a class="list-group-item {{preg_match("/addons/", app('request')->getUri()) ? "active" : null}}" href="/admin/category/{{$cat->id}}/items/{{$item->id}}/addons">Addons</a>

            <a class="list-group-item {{preg_match("/tags/", app('request')->getUri()) ? "active" : null}}" href="/admin/category/{{$cat->id}}/items/{{$item->id}}/tags">Tags</a>
            <a class="list-group-item {{preg_match("/faq/", app('request')->getUri()) ? "active" : null}}" href="/admin/category/{{$cat->id}}/items/{{$item->id}}/faq">FAQ</a>
            <a class="list-group-item {{preg_match("/requirement/", app('request')->getUri()) ? "active" : null}}" href="/admin/category/{{$cat->id}}/items/{{$item->id}}/requirements">Data
                    Tracking</a>

            @if($item->type == 'products')
                <a class="list-group-item {{preg_match("/reservation/", app('request')->getUri()) ? "active" : null}}" href="/admin/category/{{$cat->id}}/items/{{$item->id}}/reservation">Reservation</a>
            @endif
            <a class="list-group-item {{preg_match("/variation/", app('request')->getUri()) ? "active" : null}}" href="/admin/category/{{$cat->id}}/items/{{$item->id}}/variation">Variations</a>
            <a class="list-group-item {{preg_match("/shop/", app('request')->getUri()) ? "active" : null}}" href="/admin/category/{{$cat->id}}/items/{{$item->id}}/shop">Shop Settings
                @if(!$item->shop_show)
                    <span class="badge bg-warning">disabled</span>
                @endif
            </a>
        </div>
    </div>
</div>

<a href="/admin/category/{{$cat->id}}/items/{{$item->id}}"
   class="btn btn-outline-danger w-100 confirm mt-2"
   data-method="DELETE"
   data-message="Are you sure you want to remove this item?">
    <i class="fa fa-trash"></i> Remove
</a>
