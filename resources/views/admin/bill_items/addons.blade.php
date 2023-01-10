<div class="card">
    <div class="card-body">
        <h5 class="card-title">
            Product and Service Addons
        </h5>
        <p class="card-text">
            Addons allow you to discount or bundle different products and services into a base service. These
            are default prices that you can customize once added to a quote or account service (if a service).
            Addons can be removed or adjusted here and will not affect a quote or account service.
        </p>
    </div>
</div>
<table class="table mt-3">
    <thead>
    <tr>
        <td>Group Name</td>
        <td>Options</td>
        <td>Opt. Price</td>
    </tr>
    </thead>
    <tbody>
    @foreach($item->addons as $addon)
        <tr>
            <td><strong>{{$addon->name}}</strong>
                <a class="live"
                   href="/admin/category/{{$cat->id}}/items/{{$item->id}}/addons/{{$addon->id}}"><i
                        class="fa fa-edit"></i>
                </a>

                <br/>
                <small class="text-muted">{{$addon->description}}</small></td>
            <td><a class="live" data-title="Add Option to {{$addon->name}}"
                   href="/admin/category/{{$cat->id}}/items/{{$item->id}}/addons/{{$addon->id}}/add"><i
                        class="fa fa-plus"></i></a></td>
            <td>&nbsp;</td>
        </tr>
        @foreach($addon->options as $opt)
            <tr>
                <td>&nbsp;</td>
                <td><a class="live"
                       href="/admin/category/{{$cat->id}}/items/{{$item->id}}/addons/{{$addon->id}}/options/{{$opt->id}}"><i
                            class="fa fa-edit"></i></a> {{$opt->name}}
                    @if($opt->item)
                        - [{{$opt->item->code}}] {{$opt->item->name}} <strong>(max: {{$opt->max}})</strong>
                    @endif
                </td>
                <td>${{moneyFormat($opt->price)}}</td>
            </tr>
        @endforeach

    @endforeach
    </tbody>
</table>
<a class="btn btn-{{bm()}}primary mt-2 live"
   href="/admin/category/{{$cat->id}}/items/{{$item->id}}/addons/create" data-title="Create new Addon Group"><i
        class="fa fa-plus"></i> Create new Addon Group</a>



