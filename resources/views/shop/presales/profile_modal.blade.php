<form method="POST" action="/shop/presales/{{$lead->hash}}/contact">
    @csrf
    @method('POST')

    <div class="row g-4">
        <div class="col-xxl-6">
                <div class="form-floating theme-form-floating">
                    <input type="text" class="form-control" name="company" value="{{$lead->company}}">
                    <label for="company">Company Name</label>
                </div>
        </div>
        <div class="col-xxl-6">
            <div class="form-floating theme-form-floating">
                <input type="text" class="form-control" name="contact" value="{{$lead->contact}}">
                <label for="contact">Your Name</label>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <div class="col-xxl-6">
            <div class="form-floating theme-form-floating">
                <input type="text" class="form-control" name="email" value="{{$lead->email}}">
                <label for="email">E-mail Address</label>
            </div>
        </div>

        <div class="col-xxl-6">
            <div class="form-floating theme-form-floating">
                <input type="text" class="form-control" name="phone" value="{{$lead->phone}}">
                <label for="phone">Phone Number</label>
            </div>
        </div>
    </div>


    <div class="row g-4 mt-2">
        <div class="col-xxl-8">
            <div class="form-floating theme-form-floating">
                <input type="text" class="form-control" name="address" value="{{$lead->address}}">
                <label for="address">Street Address</label>
            </div>
        </div>

        <div class="col-xxl-4">
            <div class="form-floating theme-form-floating">
                <input type="text" class="form-control" name="address2" value="{{$lead->address2}}">
                <label for="address2">Suite/Unit/Building</label>
            </div>
        </div>

    </div>

    <div class="row g-4 mt-2">
        <div class="col-xxl-6">
            <div class="form-floating theme-form-floating">
                <input type="text" class="form-control" name="city" value="{{$lead->city}}">
                <label for="city">City</label>
            </div>
        </div>

        <div class="col-xxl-3">
            <div class="form-floating theme-form-floating">
                <input type="text" class="form-control" name="state" value="{{$lead->state}}">
                <label for="state">State</label>
            </div>
        </div>

        <div class="col-xxl-3">
            <div class="form-floating theme-form-floating">
                <input type="text" class="form-control" name="zip" value="{{$lead->zip}}">
                <label for="zip">Zip</label>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <div class="col-xxl-6">
            <input type="submit" class="btn theme-bg-color btn-md fw-bold text-light w-100" value="Save"/>
        </div>
    </div>




</form>
