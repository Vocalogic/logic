@if($cat->type == 'products')
    <div class="card mt-4 mb-3">
        <div class="card-body flex-grow-1">
            <h6 class="mb-3 text-center"><a href="/admin/bill_categories/products"><i class="fa fa-arrow-left"></i>
                    Products</a></h6>

            <div class="list-group">
                @foreach(\App\Models\BillCategory::where('type', 'products')->orderBy('name')->get() as $product)

                        <a class="list-group-item d-flex justify-content-between align-items-center {{preg_match("/$product->id\/items/", app('request')->getUri()) ? "active" : null}}"
                           href="/admin/category/{{$product->id}}/items/">{{$product->name}}
                            <span class="badge bg-primary">{{\App\Models\BillItem::where('bill_category_id', $product->id)->count()}}</span>
                        </a>
                @endforeach
            </div>
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
            <h6 class="mb-3 text-center"><a href="/admin/bill_categories/services"><i
                        class="fa fa-arrow-left"></i> Services</a></h6>

            <div class="list-group">
                @foreach(\App\Models\BillCategory::where('type', 'services')->orderBy('name')->get() as $service)

                    <a class="list-group-item d-flex justify-content-between align-items-center {{preg_match("/$service->id\/items/", app('request')->getUri()) ? "active" : null}}"
                       href="/admin/category/{{$service->id}}/items/">{{$service->name}}
                        <span class="badge bg-primary">{{\App\Models\BillItem::where('bill_category_id', $service->id)->count()}}</span>
                    </a>
                @endforeach
            </div>


            <div class="mt-3 text-center">
                <a href="/admin/bill_categories/products">
                    <i class="fa fa-arrow-right"></i> Switch to Products
                </a>
            </div>
        </div>
    </div>

@endif
