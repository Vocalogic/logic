@if($lead->id)
    <p>
        You are creating a new project for a <span class="text-warning">LEAD</span>. This project will not be able to
        be started until the customer approves the project; at which time the lead will be converted into an account.
    </p>

@else
    <p>
        You are creating a new project on an active account. This project will be able to be started as soon as
        the customer signs the statement of work.
    </p>
@endif

<div class="card mt-3">
    <div class="card-body">
        <form method="POST" action="/admin/projects">
            @method('POST')
            @csrf

            <x-form-input name="name" label="Project Name:" icon="folder">
                Enter a name for this project (ex. Website Redesign)
            </x-form-input>
            <x-form-input name="description" type="textarea" label="Description:" icon="comment">
                Enter a short description of this this project.
            </x-form-input>


            <div class="row mt-3">
                <div class="col-lg-12">
                    @if($lead->id)
                        <input type="hidden" name="lead_id" value="{{$lead->id}}">
                    @else
                        <input type="hidden" name="account_id" value="{{$account->id}}">
                    @endif
                    <button type="submit" class="btn btn-primary pull-right ladda" data-style="expand-left">
                        <i class="fa fa-save"></i> Create Project
                    </button>
                </div>
            </div>

        </form>
    </div>

</div>

