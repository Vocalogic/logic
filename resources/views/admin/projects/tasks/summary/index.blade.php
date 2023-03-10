<div class="card">
    <div class="card-header">
        <h5 class="card-title">{{$task->name}}
            <span class="small text-muted">| Summary of Work
                @if(!app('request')->editdesc)
                    <a class="btn btn-primary btn-sm pull-right" href="/admin/projects/{{$project->id}}/tasks/{{$task->id}}?editdesc=true"><i class="fa fa-edit"></i> edit</a>
                @endif
            </span></h5>
    </div>
    <div class="card-body">



        @if(app('request')->editdesc)
            <form method="post" action="/admin/projects/{{$project->id}}/tasks/{{$task->id}}">
                @method('PUT')
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <textarea class="tinymce" name="description">{!! $task->description !!}</textarea>
                    </div>
                    <div class="col-lg-12 mt-2">
                        <button type="submit" name="submit" class="btn btn-sm btn-primary pull-right ladda"
                                data-style="expand-left">
                            <i class="fa fa-save"></i> Save
                        </button>
                    </div>
                </div>
            </form>
        @else
            {!! $task->description !!}
        @endif
    </div>
</div>
