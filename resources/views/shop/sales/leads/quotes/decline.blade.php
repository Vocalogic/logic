<p>When a customer declines a quote, or the quote is no longer valid, you can decline it here with a
    reason.</p>

<form method="post" action="/sales/leads/{{$lead->id}}/quotes/{{$quote->id}}">
    @method('DELETE')
    @csrf
    <div class="row g-3 mb-4">
        <div class="col-lg-12 col-md-12">
            <div class="form-floating">
                <input type="text" class="form-control" name="reason" value="">
                <label>Reason for Decline</label>
                <span
                    class="helper-text">Enter a short description of why this quote was declined.</span>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-lg-12 col-md-12 mt-3">
            <input type="submit" class="btn btn-primary bg-primary rounded text-white" value="Save">
        </div>
    </div>
</form>
