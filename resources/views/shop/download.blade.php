<div>

    @if(!$enabled)
        <div class="alert alert-info">Waiting for Verification...</div>
    @elseif($thanks)
        <div class="alert alert-success">Quote emailed for review!</div>
    @else
        @if($errorMessage)
            <div class="alert alert-danger">{{$errorMessage}}</div>
        @endif
    <div class="card">
        <div class="card-body">
            <h5>Company Details</h5>
            <p class="card-text mt-2">Enter your company and/or name and email below to have your
            quote emailed to you.</p>
        <form class="mt-3">
            <div class="row g-4 mt-2">
                <div class="col-xxl-12">
                    <div class="form-floating theme-form-floating">
                        <input type="text" class="form-control" wire:model="company">
                        <label for="city">Company Name</label>
                    </div>
                </div>

                <div class="col-xxl-12">
                    <div class="form-floating theme-form-floating">
                        <input type="text" class="form-control" wire:model="contact">
                        <label for="city">Your Name</label>
                    </div>
                </div>


                <div class="col-xxl-12">
                    <div class="form-floating theme-form-floating">
                        <input type="text" class="form-control" wire:model="email">
                        <label for="city">E-mail Address</label>
                    </div>
                </div>

            </div>


            <div class="col-xxl-12 mt-3">
                <div class="col-xxl-6">
                    <input class="btn bg-primary w-100 text-white" value="Send Quote" wire:click="send">
                </div>
            </div>



        </form>
        </div>
    </div>

    @endif

</div>
