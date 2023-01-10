<form method="post" action="/admin/category/{{$cat->id}}/items/{{$item->id}}/addons{{$addon->id?"/$addon->id" : null}}">
    @method($addon->id ? "PUT" : 'POST')
    @csrf
    <h6 class="fw-bold">Addon Groups</h6>
    <p class="card-text">
        Manage new addon groups here. An example would be "Select Headset/Pick your Headset". Your description could be
        something like "This item provides a discount on a new headset with purchase of phone."
    </p>

    <div class="row mt-2">
        <div class="col-lg-4 col-md-12">
            <div class="form-floating">
                <input type="text" class="form-control" name="name" value="{{$addon->name}}">
                <label>Group Name</label>
                <span class="helper-text">Enter the group name for this addon</span>
            </div>
        </div>
        <div class="col-lg-8 col-md-12">
            <div class="form-floating">
                <input type="text" class="form-control" name="description" value="{{$addon->description}}">
                <label>Description</label>
                <span class="helper-text">Enter the description for this addon (promo, etc)</span>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-lg-6">
            <input type="submit" class="btn btn-{{bm()}}primary" value="Save">
            @if($addon->id)
                <a class="confirm pull-right btn btn-danger"
                   data-message="Are you sure you want to remove this addon category? WARNING! Any accounts/quotes that have this addon will be removed."
                   data-method="DELETE"
                   href="/admin/category/{{$cat->id}}/items/{{$item->id}}/addons/{{$addon->id}}"><i class="fa fa-trash"></i> Delete Group</a>
            @endif

        </div>
    </div>
</form>
