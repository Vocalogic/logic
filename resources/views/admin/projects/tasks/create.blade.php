<p>
    You are creating a new task in <code>{{$category->name}}</code>. Once you have
    saved the task you will be able to add additional details.
</p>
<form method="post" action="/admin/projects/{{$project->id}}/tasks?category={{$category->id}}">
    @method('POST')
    @csrf
    <x-form-input name="name" label="Task Name:" icon="bars" placeholder="Create a new widget">
        Enter a name for this new task
    </x-form-input>

    <div class="row">
        <div class="col-lg-12">
            <button type="submit" name="submit" class="btn btn-primary ladda pull-right" data-style="expand-left">
                <i class="fa fa-save"></i> Create
            </button>
        </div>
    </div>

</form>
