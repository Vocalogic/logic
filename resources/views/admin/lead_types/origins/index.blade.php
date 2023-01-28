<div class="card">
    <div class="card-body">
        <p class="card-title">Lead Origins</p>
        <p>
            Define the different ways in which you get leads. Website, Phone Call, Referral, etc.
        </p>
        <table class="table">
            <thead>
            <tr>
                <th>Origin</th>
            </tr>
            </thead>
            <tbody>
            @foreach(\App\Models\LeadOrigin::all() as $origin)
                <tr>
                    <td>
                    <a class="live" data-title="Edit {{$origin->name}}" href="/admin/origins/{{$origin->id}}">{{$origin->name}}</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <a class="btn btn-primary btn-sm" data-bs-toggle="modal" href="#newOrigin">
            <i class="fa fa-plus"></i> new origin
        </a>
    </div>
</div>

<div class="modal fade" id="newOrigin" tabindex="-1" role="dialog" aria-labelledby="liveModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create new Lead Origin</h5>
            </div>
            <div class="modal-body">
                <form method="post" action="/admin/origins">
                    @method('POST')
                    @csrf
                    <p>
                        Enter a new method in which you get a lead. Examples could be "Website", "EXPO", "Email", etc.
                    </p>
                    <div class="row g-3 mb-4">
                        <div class="col-lg-12 col-md-12">
                            <x-form-input name="name"  label="Origin Name" icon="file-text-o">
                                Enter the Lead Origin (where did a lead come from?)
                            </x-form-input>
                        </div>

                    </div>
                        <div class="col-lg-12 col-md-12 mt-3">
                            <button type="submit" class="btn btn-primary ladda pull-right" data-style="zoom-out">
                                <i class="fa fa-save"></i> Save Origin
                            </button>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>
