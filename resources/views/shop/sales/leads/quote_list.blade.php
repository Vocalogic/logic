@if(cart()->total > 0)
    <a class="btn text-center btn-primary btn-sm bg-primary w-25 text-white" href="/sales/leads/{{$lead->id}}/quotes/create">Create New Quote from Cart</a>
    @else
    <div class="alert alert-info">
        You have no items in your cart. If you wish to create a quote for a lead, you must have items in your cart.
    </div>
    @endif

<table class="table table-striped mt-4">
    <thead>
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Age</th>
        <th>MRR</th>
        <th>NRC</th>
        <th>Term</th>
        <th>Commission</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
        @foreach($lead->quotes()->where('archived', false)->get() as $quote)

            <tr {{$quote->presentable ? "class='bg-light-success'" : null}}>
                <td>
                    <a href="/sales/leads/{{$lead->id}}/quotes/{{$quote->id}}"><span class="badge bg-primary">#{{$quote->id}}</span></a>
                    @if($quote->preferred)
                        <i class="fa fa-check"></i>
                    @endif
                </td>
                <td><a href="/sales/leads/{{$lead->id}}/quotes/{{$quote->id}}">{{$quote->name}}</a></td>
                <td>{{$quote->created_at->diffForHumans()}}</td>
                <td>${{moneyFormat($quote->mrr)}}</td>
                <td>${{moneyFormat($quote->nrc)}}</td>
                <td>{{$quote->term ? "$quote->term Months" : "MTM"}}</td>
                <td>${{moneyFormat($quote->commissionable)}}</td>

                <td>
                    <div class="d-flex align-items-stretch">
                    <a href="/sales/leads/{{$lead->id}}/quotes/{{$quote->id}}/download" class="p-2"
                       data-bs-toggle="tooltip" data-bs-placement="top" data-anchor=".custom-table" title="Download"><i class="fa fa-download"></i></a>
                    <a href="/sales/leads/{{$lead->id}}/quotes/{{$quote->id}}/" class="p-2"
                       data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                    @if($quote->presentable)
                        <a href="/sales/leads/{{$lead->id}}/quotes/{{$quote->id}}/presentable" class="p-2"
                           data-bs-toggle="tooltip" data-bs-placement="top" title="Quote is Presentable"><i
                                class="fa fa-check text-success"></i></a>
                    @else
                        <a href="/sales/leads/{{$lead->id}}/quotes/{{$quote->id}}/presentable" class="p-2"
                           data-bs-toggle="tooltip" data-bs-placement="top" title="Quote is Not Presentable"><i
                                class="fa fa-exclamation-triangle text-warning"></i></a>
                    @endif


                    <a href="/sales/leads/{{$lead->id}}/quotes/{{$quote->id}}/send" class="p-2"
                       data-method="GET"
                       data-message="Are you sure you want to send this quote?"
                       data-bs-toggle="tooltip" data-bs-placement="top" title="Send Quote"><i class="fa fa-mail-forward"></i></a>
                    <a href="/sales/leads/{{$lead->id}}/quotes/{{$quote->id}}/decline" class="p-2 live"
                       data-bs-toggle="tooltip" data-bs-placement="top" title="Decline Quote"><i class="fa fa-trash text-danger"></i>
                    </a>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
