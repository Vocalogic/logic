<ul class="nav nav-tabs tab-card border-bottom-0 pt-2 fs-6 justify-content-center justify-content-md-start">
<li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#quote-open" role="tab">Open</a>
    </li>
    <li class="nav-item"><a class="nav-link " data-bs-toggle="tab" href="#quote-sold"
                            role="tab">Sold</a>
    </li>

</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="quote-open" role="tabpanel">
        @include('admin.accounts.quotes.open')
    </div>

    <div class="tab-pane fade" id="quote-sold" role="tabpanel">
        @include('admin.accounts.quotes.sold')
    </div>
</div>

<a class="btn btn-{{bm()}}primary" href="#newQuote" data-bs-toggle="modal"><i class="fa fa-plus"></i> new quote</a>


<div class="modal fade" id="newQuote" tabindex="-1" role="dialog" aria-labelledby="liveModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create new Quote</h5>
            </div>
            <div class="modal-body">
                <p class="mb-3">
                    Enter the name of the new quote. This quote will be created as a draft and you will be
                    prompted to enter services once saved.
                </p>
                <form method="post" action="/admin/accounts/{{$account->id}}/quotes">
                    @method('POST')
                    @csrf
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="name"
                                       value="{{$account->name}} Quote ({{now()->format("m/d")}})">
                                <label>Quote Name</label>
                                <span class="helper-text">Enter the name of the quote</span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-6">
                                <input type="submit" name="submit" value="Create" class="btn btn-primary">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



