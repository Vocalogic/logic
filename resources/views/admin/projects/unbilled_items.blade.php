<p>
    Select which unbilled items you want created for a new invoice. If an item is set to be billed at the end
    of a project, then it will be disabled by default. You can override this if necessary.
</p>
<form method="POST" action="/admin/projects/{{$project->id}}/unbilled">
    @csrf
    @method('POST')
    <table class="table table-striped">
        <thead class="table-light">
        <tr>
            <th>Item</th>
            <th>Qty/Price</th>
            <th>Bills</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($project->categories as $category)
            @foreach($category->items()->whereNull('invoice_id')->get() as $item)
                <tr>
                    <td>
                        [{{$item->code}}] {{$item->name}}
                    </td>
                    <td>{{$item->qty}} x ${{moneyFormat($item->price)}}</td>
                    <td>{{$item->bill_type}}</td>
                    <td>
                        <div class="form-floating">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" {{$item->bill_type != 'End' ? "checked" : null}} role="switch" value="1"
                                       id="i_{{$item->id}}"
                                       name="i_{{$item->id}}">
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        @endforeach
        </tbody>
    </table>
    <div class="row">
        <div class="col-lg-12">
            <button class="btn btn-primary pull-right btn-sm ladda" type="submit">
                <i class="fa fa-save"></i> Create Invoice
            </button>
        </div>
    </div>
</form>
