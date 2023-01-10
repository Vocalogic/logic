<div class="modal fade" id="declineModal" tabindex="-1" role="dialog" aria-labelledby="liveModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Decline Quote</h5>
            </div>

            <div class="modal-body">
                <p>When a customer declines a quote, or the quote is no longer valid, you can decline it here with a
                    reason.
                    The quote will still be viewable within the account, however will not show up in any lists.</p>

                <form method="post" action="/admin/quotes/{{$quote->id}}">
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
                            <input type="submit" class="btn btn-primary rounded" value="Save">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
