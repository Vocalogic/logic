<div>

    <div class="card">
        <div class="card-body">
            <h4>Verification Status: <b>{{$verificationStatus}}</b></h4>

            @if($verifyMethod == 'Email')
                <p class="mt-3">
                    In order to proceed you must enter a valid email address. Once submitted, you will be asked to enter
                    a 6-digit code. Once you enter the valid code, you will be able to proceed.
                </p>

                @if($errorMessage)
                    <div class="alert alert-danger">{{$errorMessage}}</div>
                @endif


                <form>
                    <div class="row g-4 mt-2">
                        <div class="col-xxl-6">
                            <div class="form-floating theme-form-floating">
                                <input type="text" class="form-control"
                                       wire:model="email" {{$verificationStatus != 'Not Started' || $lockInput? "disabled" : null}} >
                                <label for="city">E-mail Address</label>
                            </div>
                        </div>
                        <div class="col-xxl-6">
                            <input class="btn bg-primary w-100 text-white" value="Begin Verification"
                                   wire:click="startVerification" {{$verificationStatus != 'Not Started' ? "disabled" : null}}>
                        </div>
                    </div>
                    @if($inProgress && $tries < $maxAttempts)
                        <div class="row g-4 mt-2">
                            <div class="col-xxl-6">
                                <div class="form-floating theme-form-floating">
                                    <input type="text" class="form-control" wire:model="verificationInput">
                                    <label for="city">Enter Code</label>
                                </div>
                            </div>

                            <div class="col-xxl-6">
                                <input class="btn bg-primary w-100 text-white" value="Verify" wire:click="verifySubmit">
                            </div>
                        </div>
                    @endif
                </form>
            @endif

            @if($verifyMethod == 'SMS')
                <p class="mt-3">
                    In order to proceed you must enter a valid US mobile phone number that is capable of receiving SMS messages.
                    Once sent, you will be asked to enter the provided 6-digit code.
                </p>
                <form>
                    <div class="row g-4 mt-2">
                        <div class="col-xxl-6">
                            <div class="form-floating theme-form-floating">
                                <input type="text" class="form-control"
                                       wire:model="phone" {{$verificationStatus != 'Not Started' || $lockInput ? "disabled" : null}} >
                                <label for="city">Mobile Phone Number</label>
                            </div>
                        </div>
                        <div class="col-xxl-6">
                            <input class="btn bg-primary w-100 text-white" value="Begin Verification"
                                   wire:click="startVerification" {{$verificationStatus != 'Not Started' ? "disabled" : null}}>
                        </div>
                    </div>
                    @if($inProgress && $tries < $maxAttempts)
                        <div class="row g-4 mt-2">
                            <div class="col-xxl-6">
                                <div class="form-floating theme-form-floating">
                                    <input type="text" class="form-control" wire:model="verificationInput">
                                    <label for="city">Enter Code</label>
                                </div>
                            </div>

                            <div class="col-xxl-6">
                                <input class="btn bg-primary w-100 text-white" value="Verify" wire:click="verifySubmit">
                            </div>
                        </div>
                    @endif
                </form>


            @endif

        </div>
    </div>
</div>
