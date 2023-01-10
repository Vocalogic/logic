<p>
    Edit the event details below. This will update the account and dashboard calendars immediately.
</p>
<form method="post" action="/admin/events/{{$event->id}}">
    @method('PUT')
    @csrf
    <div class="row">

        <div class="col-lg-6 col-md-12">
            <div class="form-floating">
                <input type="text" class="form-control" name="post" value="{{$event->post}}">
                <label>Event Details</label>
                <span class="helper-text">Enter a short description for the calendar.</span>
            </div>
        </div>

        <div class="col-lg-6 col-md-12">
            <div class="form-floating">
                <input type="text" class="form-control" name="event" value="{{$event->event}}">
                <label>Event Date/Time</label>
                <span class="helper-text">Enter the date and time for this event.</span>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-lg-12">
            <input type="submit" name="save" value="Save" class="btn btn-primary wait" data-anchor=".modal">
        </div>
    </div>
</form>
