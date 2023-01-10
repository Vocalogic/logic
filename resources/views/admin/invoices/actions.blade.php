<div class="row">

    <a class="mb-4" href="/admin/accounts/{{$account->id}}?active=invoices"><i class="fa fa-arrow-left"></i> Back to
        Invoices</a>


    @if($invoice->balance > 0)
        <div class="col-6 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <a href="#paymentModal" data-bs-toggle="modal"><i class="fa fa-dollar fa-2x"></i>
                        <div class="mb-0">Payment</div>
                    </a>
                </div>
            </div>
        </div>





    @endif

    @if($invoice->total < 0 || $invoice->total > 0)
    <div class="col-6 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <a class="confirm"
                   data-method="GET"
                   data-loading="Sending Invoice..."
                   @if($invoice->total > 0)
                   data-message="Are you ready to send this invoice?"
                   @else
                       data-message="Are you ready to send this credit memo and apply to the account?"
                   @endif
                   href="/admin/invoices/{{$invoice->id}}/send"><i class="fa fa-send fa-2x"></i>
                    <div class="mb-0">Send {{$invoice->total > 0 ? "Invoice" : "Credit"}}</div>
                </a>
            </div>
        </div>
    </div>
    @endif


    <div class="col-6 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <a class="wait" href="/admin/invoices/{{$invoice->id}}/download"><i class="fa fa-download fa-2x"></i>
                    <div class="mb-0">Download</div>
                </a>
            </div>
        </div>
    </div>


    <div class="col-6 ">
        <div class="card">
            <div class="card-body text-center">
                <a class="confirm" href="/admin/invoices/{{$invoice->id}}/order"
                   data-method="GET"
                   data-message="Are you ready to create an order based on the items listed in this invoice?"
                ><i class="fa fa-shopping-basket fa-2x"></i>
                    <div class="mb-0">Create Order</div>
                </a>
            </div>
        </div>
    </div>
    @if($invoice->transactions()->count() == 0)

        <div class="col-6">
            <div class="card">
                <div class="card-body text-center">
                    <a href="#products" data-bs-toggle="modal"><i class="fa fa-shopping-cart fa-2x"></i>
                        <div class="mb-0">Add Product</div>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-6 ">
            <div class="card">
                <div class="card-body text-center">
                    <a class="confirm" href="/admin/invoices/{{$invoice->id}}"
                       data-method="DELETE"
                       data-loading="Removing Invoice.."
                       data-message="Are you sure you want to permanently delete this invoice?">
                        <i class="fa fa-trash fa-2x"></i>
                        <div class="mb-0">Delete</div>
                    </a>
                </div>
            </div>
        </div>
    @endif


</div>
