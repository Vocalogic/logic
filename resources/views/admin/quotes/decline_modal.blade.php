<div class="modal fade" id="declineModal" tabindex="-1" role="dialog" aria-labelledby="liveModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Decline Quote</h5>
            </div>

            <div class="modal-body">
                <p>When a customer declines a quote, or the quote is no longer valid, you can decline it here with a
                    reason. The quote will still be viewable within the account, however will not show up in any lists.
                </p>
                <div class="card border-primary">
                    <div class="card-body">


                        <form method="post" action="/admin/quotes/{{$quote->id}}">
                            @method('DELETE')
                            @csrf
                            <div class="row g-3 mb-4">
                                <div class="col-lg-12 col-md-12">
                                    <x-form-input name="reason" icon="comment" label="Reason for Decline"
                                                  placeholder="Customer decided not to proceed.">
                                        Enter a short description of why this quote was declined.
                                    </x-form-input>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="offset-4 col-lg-6">
                                    <button type="submit" class="btn btn-primary w-100 ladda" data-style="zoom-out"><i
                                            class="fa fa-close"></i> Decline Quote
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
