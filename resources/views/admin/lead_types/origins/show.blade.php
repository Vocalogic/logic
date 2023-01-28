<form method="post" action="/admin/origins/{{$origin->id}}">
    @method('PUT')
    @csrf
    <p>
        Enter a new method in which you get a lead. Examples could be "Website", "EXPO", "Email", etc.
    </p>
    <div class="row g-3 mb-4">
        <div class="col-lg-12 col-md-12">
            <x-form-input name="name" value="{{$origin->name}}" label="Origin Name" icon="file-text-o">
                Enter the Lead Origin (where did a lead come from?)
            </x-form-input>
        </div>

    </div>
    <div class="col-lg-12 col-md-12 mt-3">
        <a class="text-danger confirm" data-message="Are you sure you want to remove this origin?"
           data-method="DELETE"
           href="/admin/origins/{{$origin->id}}"><i class="fa fa-trash"></i> Remove {{$origin->name}}</a>

        <button type="submit" class="btn btn-primary ladda pull-right" data-style="zoom-out">
            <i class="fa fa-save"></i> Save Origin
        </button>

    </div>
</form>
