<div class="scheduleForm">
    <p class="card-text">
        You are scheduling a <code>Service Suspension</code> for one or many services. Please enter a reason
        below and enter a date for the services that are to be suspended. Upon submitting this form, the customer
        will be immediately emailed of a pending suspension with the services listed and the reason.
    </p>
    <form method="post" action="/admin/accounts/{{$account->id}}/suspend">
        @csrf
        @method('POST')
        <div class="row mt-3">
            <div class="col-lg-12">
                <div class="form-floating">
                    <input type="text" class="form-control" name="reason">
                    <label>Suspension Reason</label>
                    <span class="helper-text">Enter a short description of why this service is being suspended</span>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-12">
                <table class="table table-sm">
                    <thead>
                    <tr>
                        <th>Service</th>
                        <td>Suspend Date</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($account->items as $item)
                        <tr>
                            <td>[{{$item->item->code}}] - {{$item->item->name}}</td>
                            <td><input type="date" class="form-control" name="s_{{$item->id}}"
                                       value="{{$item->suspend_on ? $item->suspend_on->format("Y-m-d") : null}}"></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-6">
                <input type="submit" class="btn btn-{{bm()}}primary wait" data-anchor=".scheduleForm" value="Schedule Suspension">
            </div>
        </div>


    </form>
</div>
