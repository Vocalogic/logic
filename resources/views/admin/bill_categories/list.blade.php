<div class="card mt-2">

    <table class="table align-middle">
        <thead>
        <tr>
            <td></td>
            <td>Category</td>
            <td>Name in Shop</td>
            <td>Description</td>
            <td>Items</td>
        </tr>
        </thead>
        <tbody>
        @foreach(\App\Models\BillCategory::where('type', $type)->get() as $cat)
            <tr>
                <td><a data-title="Edit {{$cat->name}}" class="live" href="/admin/bill_categories/{{$type}}/{{$cat->id}}"><i class="fa fa-edit"></i></a></td>
                <td><a href="/admin/category/{{$cat->id}}/items"><span class="text-secondary">{{$cat->name}}</span></a>
                    <div class="pull-right">
                        <a href="/admin/categories/{{$cat->id}}/tag_categories"><span class="badge bg-info">Tags</span></a>
                    </div>
                </td>
                <td>{{$cat->shop_name}}
                    @if(!$cat->shop_show)
                        <span class="badge bg-danger">Not Listed</span>
                    @endif
                </td>
                <td>{{$cat->description}}</a></td>
                <td>{{$cat->items()->count()}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
