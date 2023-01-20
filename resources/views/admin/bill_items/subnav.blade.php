@if($cat->type == 'products')
    <div class="card mt-4 mb-3">
        <div class="card-body flex-grow-1">
            <h6 class="card-title mb-3 text-center"><a href="/admin/bill_categories/products"><i class="fa fa-arrow-left"></i> Products</a></h6>

            <ul class="list-group list-group-custom">
                @foreach(\App\Models\BillCategory::where('type', 'products')->orderBy('name')->get() as $product)
                    <li class="list-group-item {{preg_match("/$product->id\/items/", app('request')->getUri()) ? "active" : null}}">
                        <a class="color-600" href="/admin/category/{{$product->id}}/items/">{{$product->name}}</a>
                        <span class="badge bg-primary pull-right">({{\App\Models\BillItem::where('bill_category_id', $product->id)->count()}})</span>
                    </li>
                @endforeach
            </ul>
            <div class="mt-3 text-center">
                <a href="/admin/bill_categories/services">
                    <i class="fa fa-arrow-right"></i> Switch to Services
                </a>
            </div>
        </div>
    </div>
@endif


@if($cat->type == 'services')

    <div class="card mt-4 mb-3">
        <div class="card-body">
            <h6 class="card-title mb-3 text-center"><a href="/admin/bill_categories/services"><i class="fa fa-arrow-left"></i> Services</a></h6>
            <ul class="list-group list-group-custom">
                @foreach(\App\Models\BillCategory::where('type', 'services')->orderBy('name')->get() as $service)
                    <li class="list-group-item {{preg_match("/$service->id\/items/", app('request')->getUri()) ? "active" : null}}">
                        <a class="color-600" href="/admin/category/{{$service->id}}/items/">{{$service->name}}</a>
                        <span class="badge bg-primary pull-right">({{\App\Models\BillItem::where('bill_category_id', $service->id)->count()}})</span>
                    </li>
                @endforeach
            </ul>
            <div class="mt-3 text-center">
                <a href="/admin/bill_categories/products">
                    <i class="fa fa-arrow-right"></i> Switch to Products
                </a>
            </div>
        </div>
    </div>

@endif
