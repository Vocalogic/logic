<div class="row">
    <div class="col-lg-6">
        @include('shop.sales.leads.questions')


    </div>


    <div class="col-lg-6">
        <form method="POST" action="/sales/leads/{{$lead->id}}">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-floating theme-form-floating">
                        <textarea type="text" class="form-control" id="discovery" name="discovery" style="height:200px;">{!! $lead->discovery !!}</textarea>
                        <label for="contact">Discovery Notes</label>
                        <span class="helper-text">Enter any notes for this lead used when creating a quote.</span>
                    </div>

                    <input type="submit" name="save" class="btn bg-primary text-white btn-md" value="Save Notes">

                </div>
            </div>

        </form>
    </div>

</div>
