<div class="card">
    <div class="card-body">

        <h6 class="card-title">Assigned Tags</h6>
        <p class="card-text">Tags are used to filter products and services by showing what other
            items are options or alternatives. You can assign as many tags to your items as you need.</p>
    </div>

</div>
<a class="mt-2 btn btn-{{bm()}}primary live mt-3"
   data-title="Assign Tag to {{$item->name}}"
   href="/admin/category/{{$cat->id}}/items/{{$item->id}}/tags/create">
    <i class="fa fa-plus"></i> Assign new Tag
</a>

<table class="table mt-3">
    <thead>
    <tr>
        <th>Tag</th>
        <th>Description</th>
    </tr>
    </thead>
    <tbody>
    @foreach($item->tags as $tag)
        <tr>
            <td><a href="/admin/category/{{$cat->id}}/items/{{$item->id}}/remove/{{$tag->id}}"><i
                        class="fa fa-trash"></i></a> {{$tag->tag->category->name}} :: {{$tag->tag->name}}
            </td>
            <td>{{$tag->tag->description}}</td>
        </tr>
    @endforeach

    </tbody>
</table>
<a class="mt-2 btn btn-{{bm()}}primary" href="/admin/category/{{$cat->id}}/items/{{$item->id}}/faq">
    Save and Continue
</a>
