<div class="row areaModal">

    <div class="col-lg-3">
        <p class="text-center mt-4">
            <i class="fa fa-user fa-4x text-primary"></i>
        </p>
    </div>


    <div class="col-lg-9">

        <p>
            Enter the account information below, and you will be redirected to the new account page to complete
            setup and add services.
        </p>


        <form method="post" action="/admin/accounts">
            @csrf
            @method('POST')
            <div class="row">

                <div class="col-lg-12">
                    <x-form-input label="Company Name" name="name" icon="building">
                        Enter the new company name
                    </x-form-input>
                    <x-form-input label="Primary Contact" name="contact" icon="user">
                        Enter the primary contact's name
                    </x-form-input>
                    <x-form-input label="E-mail Address" name="email" icon="mail-reply">
                        Enter the contact's Email Address
                    </x-form-input>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-lg-12 ">
                    <button type="submit"  class="btn btn-primary ladda pull-right" data-style="zoom-out">
                        <i class="fa fa-save"></i> Create Account
                    </button>
                </div>
            </div>

        </form>

    </div>
</div>

