<div class="card mt-3">
    <div class="card-body">
        <h5 class="card-title">Billable Items
            <span class="small text-muted">| Additional Items/Expenses to Be Billed
            </span>
        </h5>
        <a class="live btn btn-sm btn-primary"
           data-title="Add Product to {{$category->name}}"
           href="/admin/projects/{{$project->id}}/categories/{{$category->id}}/items/create">
            <i class="fa fa-plus"></i> Add Product
        </a>
        <form method="POST" action="/admin/projects/{{$project->id}}/categories/{{$category->id}}/items">
            @method('POST')
            @csrf
            <table class="table mt-2 table-striped">
                <thead class="table-light">
                <tr>
                    <th width="65%">Item</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Bills</th>
                </tr>
                </thead>
                @foreach($category->items as $item)
                    <tr>
                        <td>
                            <strong>
                                <a class="live"
                                   data-title="Edit {{$item->code}}"
                                   href="/admin/projects/{{$project->id}}/categories/{{$category->id}}/items/{{$item->id}}">
                                    [{{$item->code}}] {{$item->name}}</a>
                            </strong>

                            <br/>
                            <small class="text-muted">{{$item->description}}</small>
                        </td>
                        <td>${{moneyFormat($item->price)}}</td>
                        <td>{{$item->qty}}</td>
                        <td>${{moneyFormat(bcmul($item->price * $item->qty,1))}}</td>
                        <td>{{$item->bill_type}}</td>
                    </tr>
                @endforeach
                <tbody>
                <tr>
                    <td>
                        <input type="text" class="form-control" name="custom_item"
                               placeholder="Enter new custom billable item..">
                    </td>
                    <td><input type="text" class="form-control" name="price" placeholder="Enter Price"></td>
                    <td><input type="text" class="form-control" name="qty" value="1"></td>
                    <td>
                        <button type="submit" name="add" class="btn btn-primary ladda" data-effect="zoom-out">+</button>
                    </td>
                    <td>&nbsp;</td>
                </tr>
                </tbody>
            </table>
        </form>

    </div>
</div>
