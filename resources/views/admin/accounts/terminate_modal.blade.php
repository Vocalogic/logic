<div class="scheduleForm">
    <p class="card-text">
        You are scheduling a <code>Service Termination</code> for one or many services. Please enter a reason
        below and enter a date for the services that are to be terminated. Upon submitting this form, the customer
        will be immediately emailed of a pending termination with the services listed and the reason.
    </p>
    <form method="post" action="/admin/accounts/{{$account->id}}/terminate">
        @csrf
        @method('POST')
        <div class="row mt-3">
            <div class="col-lg-12">
                <div class="form-floating">
                    <input type="text" class="form-control" name="reason">
                    <label>Termination Reason</label>
                    <span class="helper-text">Enter a short description of why this service is being terminated</span>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-12">
                <table class="table table-sm">
                    <thead>
                    <tr>
                        <th>Service</th>
                        <td>Termination Date</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($account->items as $item)
                        <tr>
                            <td>[{{$item->item->code}}] - {{$item->item->name}}</td>
                            <td><input type="date" class="form-control" name="s_{{$item->id}}"
                                       value="{{$item->terminate_on ? $item->terminate_on->format("Y-m-d") : null}}"></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-6">
                <input type="submit" class="btn btn-{{bm()}}primary wait" data-anchor=".scheduleForm" value="Schedule Termination">
            </div>
        </div>
    </form>
</div>
