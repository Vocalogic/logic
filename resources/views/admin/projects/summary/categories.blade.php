<div class="card">
    <div class="card-body">
        <h5 class="card-title">Task Categories
            <span class="small text-muted">| Tasks Grouped into Categories
                @if(!app('request')->editsow)
                    <a class="btn btn-primary live btn-sm pull-right"
                       data-title="Create new Task Category"
                       href="/admin/projects/{{$project->id}}/categories/create"><i class="fa fa-plus"></i> new category</a>
                @endif
            </span></h5>
        <div class="row">
            @foreach($project->categories as $category)
                <div class="col-xxl-4 col-lg-6 col-md-6 col-sm-12 project-card">
                    @include('admin.projects.categories.single', ['category' => $category])
                </div>
            @endforeach
        </div>
    </div>
</div>
