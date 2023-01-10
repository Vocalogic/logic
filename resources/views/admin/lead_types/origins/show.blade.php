<form method="post" action="/admin/origins/{{$origin->id}}">
    @method('PUT')
    @csrf
    <p>
        Enter a new method in which you get a lead. Examples could be "Website", "EXPO", "Email", etc.
    </p>
    <div class="row g-3 mb-4">
        <div class="col-lg-12 col-md-12">
            <div class="form-floating">
                <input type="text" class="form-control" name="name" value="{{$origin->name}}">
                <label>Origin Name</label>
                <span class="helper-text">Enter the Lead Origin</span>
            </div>
        </div>

    </div>
    <div class="col-lg-12 col-md-12 mt-3">
        <input type="submit" class="btn btn-primary rounded" value="Save">
        <a class="pull-right btn btn-danger confirm" data-message="Are you sure you want to remove this origin?"
           data-method="DELETE"
           href="/admin/origins/{{$origin->id}}"><i class="fa fa-trash"></i> Remove {{$origin->name}}</a>
    </div>
</form>
