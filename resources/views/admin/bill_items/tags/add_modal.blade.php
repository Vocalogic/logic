<div class="card border-primary">
    <div class="card-body">

        <form method="post" action="/admin/category/{{$cat->id}}/items/{{$item->id}}/tags">
            @method('POST')
            @csrf
            <div class="row mt-2">
                @props(['opts' => \App\Models\Tag::selectable($cat)])
                <div class="col-lg-6">
                    <div class="row mt-3">
                        <div class="col-lg-4">
                            <label class="form-label">Select Tag</label>
                        </div>
                        <div class="col-lg-8">
                            <fieldset class="form-icon-group left-icon position-relative">
                                {!! Form::select('tag', \App\Models\Tag::selectable($cat), null, ['class' => 'form-control']) !!}
                                <div class="form-icon position-absolute">
                                    <i class="fa fa-pencil"></i>
                                </div>
                            </fieldset>
                            <span class="helper-text">Select a tag to add to item.</span>
                        </div>
                    </div>
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
