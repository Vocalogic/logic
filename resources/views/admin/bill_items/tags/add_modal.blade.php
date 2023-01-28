<div class="card border-primary">
    <div class="card-body">

        <form method="post" action="/admin/category/{{$cat->id}}/items/{{$item->id}}/tags">
            @method('POST')
            @csrf
            <div class="row mt-2">
                @props(['opts' => \App\Models\Tag::selectable($cat)])
                <div class="col-lg-6">
                    <x-form-select name="tag" label="Select Tag" :options="$opts">
                        Select a tag to add to item.
                    </x-form-select>
                    <button type="submit" class="btn btn-primary ladda pull-right mt-3" data-style="zoom-out">
                        <i class="fa fa-save"></i> Apply Tag
                    </button>
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
    </div>
</div>
