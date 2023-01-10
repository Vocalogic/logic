<section class="contact-box-section" style="padding-top: 5px;">
    <div class="right-sidebar-box">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-md-4 mb-3 custom-form">
                    <label for="companyName" class="form-label">Company Name</label>
                    <div class="custom-input">
                        <input type="text" class="form-control" id="companyName" wire:model="info.company"
                               placeholder="Your Company Name">
                        <i class="fa-solid fa-building"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-md-4 mb-3 custom-form">
                    <label for="contactName" class="form-label">Your Name</label>
                    <div class="custom-input">
                        <input type="text" class="form-control" id="contactName" wire:model="info.contact"
                               placeholder="Your Name">
                        <i class="fa-solid fa-user"></i>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-6">
                <div class="mb-md-4 mb-3 custom-form">
                    <label for="email" class="form-label">Your Email</label>
                    <div class="custom-input">
                        <input type="text" class="form-control" id="email" wire:model="info.email"
                               placeholder="Your email address">
                        <i class="fa-solid fa-mail-forward"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-md-4 mb-3 custom-form">
                    <label for="phone" class="form-label">Contact Phone Number</label>
                    <div class="custom-input">
                        <input type="text" class="form-control" id="phone" wire:model="info.phone"
                               placeholder="Your Phone Number">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-6">
                <div class="mb-md-4 mb-3 custom-form">
                    <label for="password" class="form-label">Create Password</label>
                    <div class="custom-input">
                        <input type="password" class="form-control" id="password" wire:model="info.password"
                               placeholder="Enter a password for your account">
                        <i class="fa-solid fa-passport"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-md-4 mb-3 custom-form">
                    <label for="password2" class="form-label">Re-enter Password</label>
                    <div class="custom-input">
                        <input type="password" class="form-control" id="password2" wire:model="info.password2"
                               placeholder="Re-enter your password">
                        <i class="fa-solid fa-passport"></i>
                    </div>
                </div>
            </div>
        </div>



        <div class="row mt-2">
            <div class="col-md-8">
                <div class="mb-md-4 mb-3 custom-form">
                    <label for="address" class="form-label">Company Address</label>
                    <div class="custom-input">
                        <input type="text" class="form-control" id="address" wire:model="info.address"
                               placeholder="Company Street Address">
                        <i class="fa-solid fa-address-book"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-md-4 mb-3 custom-form">
                    <label for="address2" class="form-label">Suite/Unit</label>
                    <div class="custom-input">
                        <input type="text" class="form-control" id="address2" wire:model="info.address2"
                               placeholder="ex. Suite 101">
                        <i class="fa-solid fa-address-book"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-6">
                <div class="mb-md-4 mb-3 custom-form">
                    <label for="city" class="form-label">City</label>
                    <div class="custom-input">
                        <input type="text" class="form-control" id="city" wire:model="info.city"
                               placeholder="City">
                        <i class="fa-solid fa-map-location"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="mb-md-4 mb-3 custom-form">
                    <label for="state" class="form-label">State</label>
                    <div class="custom-input">
                        <input type="text" class="form-control" id="state" wire:model="info.state"
                               placeholder="State">
                        <i class="fa-solid fa-map"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-md-4 mb-3 custom-form">
                    <label for="zip" class="form-label">Zip Code</label>
                    <div class="custom-input">
                        <input type="text" class="form-control" id="zip" wire:model="info.zip"
                               placeholder="Zip Code">
                        <i class="fa-solid fa-map-marker"></i>
                    </div>
                </div>
            </div>

        </div>


        <div class="row mt-2">
            <button class="btn text-white bg-success" wire:click="saveInfo"><i class="fa fa-floppy-disk"></i> &nbsp; Save Contact Info</button>
        </div>

    </div>

</section>
