<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Data Requirements</h5>
                <p>
                    There may be times where you need to record information about a particular service or product
                    that you are quoting or have sold. This feature will allow you to add extra information for when
                    reviewing the account service or selling a quote. Here you can ask either your customer or
                    as an administrator add specific information.
                </p>
            </div>
        </div>
    </div>

    <div class="col-lg-8">

        @if(!$item->id)
            <div class="alert border-info">
                Once an item has been created, you can apply data requirements.
            </div>
        @else
        <table class="table table-sm table-striped">
            <thead>
            <tr>
                <th>Item Requirement</th>
                <th>Required for Sale</th>
                <th>Options</th>
            </tr>
            </thead>
            <tbody>
                @foreach($item->meta as $meta)
                <tr>
                    <td><a class="live" data-title="Update Requirement" href="/admin/category/{{$item->category->id}}/items/{{$item->id}}/meta/{{$meta->id}}"><i class="fa fa-edit"></i></a>
                     {{$meta->item}}
                    <br><small class="text-muted">{{$meta->description}}</small>
                    </td>
                    <td>{{$meta->required_sale ? "Yes" : "No"}}</td>
                    <td>{{$meta->opts}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
            <a class="mt-4 live btn btn-{{bm()}}primary" data-title="Add new Requirement" href="/admin/category/{{$item->category->id}}/items/{{$item->id}}/meta"><i class="fa fa-plus"></i> Add new Requirement</a>

    @endif

    </div>

</div>
