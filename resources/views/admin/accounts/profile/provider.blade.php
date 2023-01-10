<div class="card mt-3">
    <div class="card-body">
        <p class="card-title">Select PBX Provider</p>
        <p>In order to create or assign a PBX you need to select which provider
        you will be associating this account to.</p>
        <form method="post" action="/admin/accounts/{{$account->id}}" class="mt-2">
            @method('PUT')
            @csrf
            <div class="col-lg-12 col-md-12 mt-2">
                <div class="form-floating">
                    {!! Form::select('provider_id', \App\Models\Provider::where('enabled', true)->pluck("name", "id")->all(),null, ['class' => 'form-control']) !!}
                    <label>Select Provider</label>
                    <span class="helper-text">Select the provider to use for this account.</span>
                </div>

                <input type="submit" name="save" value="Save Provider" class="btn btn-{{bm()}}primary wait mt-2">
            </div>

        </form>
    </div>
</div>
