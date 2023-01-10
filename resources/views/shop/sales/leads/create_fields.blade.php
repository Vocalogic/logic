<form method="POST" action="/sales/leads">
    @csrf
    @method('POST')
    <div class="row">
        <div class="col-lg-6">
            <div class="form-floating theme-form-floating">
                <input type="text" class="form-control" id="company" name="company">
                <label for="company">Company Name</label>
                <span class="helper-text">Enter company name for lead</span>
            </div>
        </div>


        <div class="col-lg-6">
            <div class="form-floating theme-form-floating">
                <input type="text" class="form-control" id="contact" name="contact">
                <label for="contact">Primary Contact</label>
                <span class="helper-text">Enter the primary contact</span>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-6">
            <div class="form-floating theme-form-floating">
                <input type="text" class="form-control" id="email" name="email">
                <label for="email">Email Address</label>
                <span class="helper-text">Enter email address of primary contact</span>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-floating theme-form-floating">
                <input type="text" class="form-control" id="phone" name="phone">
                <label for="phone">Contact Phone</label>
                <span class="helper-text">Enter contact phone number</span>
            </div>
        </div>

    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="form-floating theme-form-floating">
                <input type="text" class="form-control" id="address" name="address">
                <label for="address">Business Address</label>
                <span class="helper-text">Enter service/company address</span>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-6">
            <div class="form-floating theme-form-floating">
                <input type="text" class="form-control" id="city" name="city">
                <label for="address">City</label>
                <span class="helper-text">Enter City</span>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-floating theme-form-floating">
                <input type="text" class="form-control" id="state" name="state">
                <label for="state">State</label>
                <span class="helper-text">Enter State</span>
            </div>
        </div>

        <div class="col-lg-2">
            <div class="form-floating theme-form-floating">
                <input type="text" class="form-control" id="zip" name="zip">
                <label for="state">Zip</label>
                <span class="helper-text">Enter Zip Code</span>
            </div>
        </div>



    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            <input type="submit" class="btn btn-primary bg-primary w-25 text-white" value="Save Lead">
        </div>
    </div>

</form>



