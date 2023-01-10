<p class="card-text">
    You are assigning ownership to <strong>{{$item->name}}</strong>. This will show that the person selected below
    is working on provisioning a service or responsible for shipping the item selected.
</p>

<form method="post" action="/admin/orders/{{$order->id}}/items/{{$item->id}}/assign">
    @method('POST')
    @csrf
    <h6 class="fw-bold">Select Assignment</h6>
    <div class="row mt-2">
        <div class="col-lg-12 col-md-12">
            <div class="form-floating">
                {!! Form::select('assigned_id', array_replace([0 => '-- Select User --'], \App\Models\User::where('account_id', 1)->pluck("name", "id")->all()), $item->assigned_id, ['class' => 'form-control']) !!}
                <label>Assigned User</label>
                <span class="helper-text">Select the assigned user for this item.</span>
            </div>
            <div class="mt-3">
                <input type="submit" name="save" class="btn btn-{{bm()}}primary" value="Update Assignment">
            </div>
        </div>
    </div>
</form>
