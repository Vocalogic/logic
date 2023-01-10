<div class="card">
    <div class="card-body">
        <h5 class="card-title">
            Tag Categories in {{$category->name}}
        </h5>
        <p class="card-text">
            Create tags to organize your products and services for easy searching. Use categories like
            Make, Model, etc.
        </p>

        <ul class="list-group list-group-custom">
            @foreach(\App\Models\TagCategory::where('bill_category_id', $category->id)->orderBy('name')->get() as $cat)
                <li class="list-group-item">
                    <a href="/admin/categories/{{$category->id}}/tag_categories/{{$cat->id}}/tags">{{$cat->name}}</a>
                    <a href="/admin/categories/{{$category->id}}/tag_categories/{{$cat->id}}"> <i class="fa fa-edit"></i></a></li>
                </li>
            @endforeach
        </ul>

    </div>
    <div class="card-footer">
        <a href="/admin/categories/{{$category->id}}/tag_categories/create" class="btn btn-primary"><i class="fa fa-plus"></i> Create new
            Category</a>
    </div>

</div>
