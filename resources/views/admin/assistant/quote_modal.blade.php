<div class="sModalArea">

    <form method="POST" action="/admin/cart/{{$uid}}/command/quote">
        @method("POST")
        @csrf
        <div class="row g-2">
            <div class="col-md-6 col-6">
                <div class="form-floating">
                    <input type="text" class="form-control" name="company">
                    <label>Company Name</label>
                    <span class="helper-text">Enter company name for quote</span>
                </div>
            </div>

            <div class="col-md-6 col-6">
                <div class="form-floating">
                    <input type="text" class="form-control" name="contact">
                    <label>Contact Name</label>
                    <span class="helper-text">Enter Contact Name.</span>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-6 col-6">
                <div class="form-floating">
                    <input type="text" class="form-control" name="email">
                    <label>Email Address</label>
                    <span class="helper-text">Enter Email Address for Quote</span>
                </div>
            </div>

        </div>


        <div class="col-lg-12 mt-2">
            <button type="submit" name="submit" value="Convert Cart" class="btn btn-primary ladda btn-sm pull-right">
                <i class="fa fa-dollar"></i> Convert to Quote
            </button>
        </div>
    </form>


</div>
