<div class="row">
    <div class="col-lg-12">
        <p>
            When adding a new affiliate, if you plan to commission them, then you should either either
            MRR or SPIFF. A SPIFF is {number} * monthly services. For example, if the customer invoice
            for monthly services is $200 and you have a 2 set for SPIFF. The affiliate would get a commission
            of $400. MRR percentage on the other hand gives recurring amounts of services only each month.
            20% MRR of $100 would be $20. There would be a commission of $20 generated monthly.
        </p>
    </div>

    <div class="col-lg-12 mt-3">
        <div class="card border-primary">
            <div class="card-body">
                <form method="POST" action="/admin/affiliates{{$affiliate->id ? "/$affiliate->id" : null}}">
                    @csrf
                    @method($affiliate->id ? 'PUT' : 'POST')
                    <x-form-input name="name" icon="user" label="Affiliate Name" value="{{$affiliate->name}}">
                        Enter the first and last name of the affiliate
                    </x-form-input>

                    <x-form-input name="email" icon="mail-reply" label="Affiliate Email" value="{{$affiliate->email}}">
                        Enter the email address for the affiliate.
                    </x-form-input>

                    <x-form-input name="company" icon="building-o" label="Company Name" value="{{$affiliate->company}}">
                        Enter the company name for the affiliate if applicable.
                    </x-form-input>

                    <x-form-input name="mrr" icon="repeat" label="MRR Percentage" value="{{$affiliate->mrr}}">
                        Enter the MRR percentage to give
                    </x-form-input>

                    <x-form-input name="spiff" icon="subscript" label="SPIFF in Months" value="{{$affiliate->spiff}}">
                        Enter the number of months to payout as a SPIFF
                    </x-form-input>

                    @if($affiliate->id)
                    <a class="text-danger confirm" data-message="Are you sure you want to remove this affiliate?"
                        data-method="DELETE"
                        href="/admin/affiliates/{{$affiliate->id}}">
                        <i class="fa fa-times"></i> Remove/Deactivate {{$affiliate->name}}
                    </a>
                    @endif

                    <button type="submit" class="btn btn-primary ladda float-end" data-style="slide-left">
                        <i class="fa fa-save"></i> Save Affiliate
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
