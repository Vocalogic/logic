<p class="card-text">
    Creating a package builder will allow customers that visit your shop to build a
    quote based on answers to basic questions.
</p>
<form method="POST" class="buildForm"
      action="/admin/package_builds/{{$build->id ? "$build->id" : null}}">
    @csrf
    @method($build->id ? 'PUT' : "POST")
    <div class="row">
        <div class="col-lg-12">
            <div class="form-floating">
                <input type="text" class="form-control" name="name" value="{{$build->name}}">
                <label>Name:</label>
                <span class="helper-text">Enter the name of the package such as (i.e. Server Management, IT Support, VOIP Service, etc)</span>
            </div>
        </div>


        <div class="col-lg-12 mt-3">
            <div class="form-floating">
                <textarea class="form-control" name="description" style="height: 100px;">{!! $build->description !!}
                </textarea>
                <label>Description:</label>
                <span class="helper-text">Enter a brief description of this service</span>
            </div>
        </div>

    </div>
    <div class="row mt-3">
        <div class="col-lg-6">
            @if($build->id)
                <a class="confirm text-danger" data-message="Are you sure you want to remove this section?"
                   data-method="DELETE"
                   href="/admin/package_builds/{{$build->id}}">
                    <i class="fa fa-times"></i> Remove Package Build
                </a>
            @endif
        </div>
        <div class="col-lg-6">
            <input type="submit" class="btn btn-primary pull-right wait" data-anchor=".buildForm" value="Save Build">
        </div>
    </div>
</form>
