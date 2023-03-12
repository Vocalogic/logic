<p>
    This process will allow you to add a product that has an expense tied to it to a project. You should not
    add any hourly items here as it is calculated through the settings of the category.
</p>

    <table class="table datatable itemTable">
        <thead>
        <tr>
            <th>Item</th>
            <th>Price</th>
        </tr>
        </thead>
        <tbody>
        @foreach(\App\Models\BillCategory::with('items')->where('type', \App\Enums\Core\BillItemType::PRODUCT)->get() as $cat)

            @foreach($cat->items as $item)
                <tr>
                    <td>
                        <a href="/admin/projects/{{$project->id}}/categories/{{$category->id}}/items/add/{{$item->id}}">
                            [{{$item->code}}] {{$item->name}}</a><br/><small
                            class="text-muted">{{$cat->name}}</small></td>
                    <td>${{moneyFormat($item->nrc)}}</td>
                </tr>
            @endforeach
        @endforeach


        </tbody>
    </table>
<script>
    $('.datatable')
        .dataTable({
            responsive: true,
        });
</script>
