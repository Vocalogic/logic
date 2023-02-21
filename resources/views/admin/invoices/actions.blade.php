<div class="row">
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
                <a class="wait" data-effect="orbit" href="/admin/invoices/{{$invoice->id}}/download"><i class="fa fa-download fa-2x"></i>
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

    @if($invoice->status == \App\Enums\Core\InvoiceStatus::SENT->value)
        <div class="col-12 mt-3">

            <div class="card">
                <div class="card-body">
                    <i class="fa fa-info-circle"></i> This invoice is currently due on {{$invoice->due_on->format("m/d/y")}}.
                    <a class="live"
                       data-title="Change Due Date"
                       href="/admin/invoices/{{$invoice->id}}/due">Change due date?</a>
                </div>
            </div>
        </div>
    @endif


</div>
