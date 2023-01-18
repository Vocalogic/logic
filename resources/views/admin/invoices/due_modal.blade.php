<form method="POST" action="/admin/invoices/{{$invoice->id}}/due" class="dueForm">
    @method('POST')
    @csrf
    <div class="row">
        <div class="col-lg-12">
            <div class="form-floating">
                <input type="date" class="form-control" name="due_on" value="{{$invoice->due_on?->format("Y-m-d")}}">
                <label>Enter Due Date</label>
                <span class="helper-text">Enter a new due date for Invoice #{{$invoice->id}}</span>
            </div>
        </div>
        <div class="col-lg-12 mt-3">
            <input type="submit" name="submit" value="Save Due Date" class="btn btn-primary wait"
                   data-anchor=".dueForm">
        </div>
    </div>
</form>
