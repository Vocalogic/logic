<div class="card">
    <div class="card-body">
        <h5 class="card-title">
            Tags in {{$cat->name}}
        </h5>
        <p class="card-text">
            {{$cat->description}}
        </p>

        <ul class="list-group list-group-custom">
            @foreach($cat->tags as $tag)
                <li class="list-group-item">
                    <a href="/admin/categories/{{$category->id}}/tag_categories/{{$cat->id}}/tags/{{$tag->id}}">{{$tag->name}}</a>
                </li>
            @endforeach
        </ul>

    </div>
    <div class="card-footer">
        <a href="/admin/categories/{{$category->id}}/tag_categories/{{$cat->id}}/tags/create" class="btn btn-primary"><i class="fa fa-plus"></i>
            Create new
            {{$category->name}} Tag</a>
    </div>


</div>
