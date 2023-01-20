<form method="POST" action="/admin/invoices/{{$invoice->id}}/due" class="dueForm">
    @method('POST')
    @csrf
    <div class="row">
        @props(['value' => $invoice->due_on?->format("Y-m-d")])
        <x-form-input name="due_on" type="date" :value="$value" label="Enter Due Date" icon="calendar">
            Enter a new due date for Invoice #{{$invoice->id}}
        </x-form-input>

        <div class="offset-4 col-lg-8 mt-3">
            <input type="submit" name="submit" value="Save" class="btn btn-primary wait w-100"
                   data-anchor=".dueForm">
        </div>
    </div>
</form>
