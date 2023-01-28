<div class="card border-primary">
    <div class="card-body">


        <form method="post"
              action="/admin/category/{{$cat->id}}/items/{{$item->id}}/addons{{$addon->id?"/$addon->id" : null}}">
            @method($addon->id ? "PUT" : 'POST')
            @csrf
            <h6 class="fw-bold">Addon Groups</h6>
            <p class="card-text">
                Manage new addon groups here. An example would be "Select Headset/Pick your Headset". Your description
                could be
                something like "This item provides a discount on a new headset with purchase of phone."
            </p>

            <div class="row mt-2">
                <x-form-input name="name" value="{{$addon->name}}" label="Group Name" icon="columns">
                    Enter the group name for the addon
                </x-form-input>
                <x-form-input name="description" value="{{$addon->description}}" label="Description" icon="comment">
                    Enter a short helper description for the addon
                </x-form-input>
            </div>
            <div class="row mt-3">
                <div class="col-lg-12">
                    @if($addon->id)
                        <a class="confirm text-danger"
                           data-message="Are you sure you want to remove this addon category? WARNING! Any accounts/quotes that have this addon will be removed."
                           data-method="DELETE"
                           href="/admin/category/{{$cat->id}}/items/{{$item->id}}/addons/{{$addon->id}}"><i
                                class="fa fa-trash"></i> Delete Group</a>
                    @endif

                    <button type="submit" class="btn btn-primary ladda pull-right" data-style="zoom-out">
                        <i class="fa fa-save"></i> Save Addon
                    </button>


                </div>
            </div>
        </form>
    </div>
</div>
