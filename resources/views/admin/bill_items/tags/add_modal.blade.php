<form method="post" action="/admin/category/{{$cat->id}}/items/{{$item->id}}/tags">
    @method('POST')
    @csrf
    <h6 class="fw-bold">Select Tag to Add</h6>
    <div class="row mt-2">
        <div class="col-lg-6 col-md-12">
            <div class="form-floating">
                {!! Form::select('tag', \App\Models\Tag::selectable($cat), null, ['class' => "form-select"]) !!}
                <label>Select Tag</label>
                <span class="helper-text">Select tag to add to item.</span>
            </div>
            <input type="submit" name="submit" class="btn btn-primary" value="Add Tag">
        </div>
        <div class="col-lg-6">
            <h6 class="fw-bold">Assigned Tags</h6>

            <table class="table">
                <thead>
                <tr>
                    <th>Tag</th>
                </tr>
                </thead>
                <tbody>
                @foreach($item->tags as $tag)
                    <tr>
                        <td>{{$tag->tag->name}}</td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>


</form>
