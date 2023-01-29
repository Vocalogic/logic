<form method="post" action="/admin/vendors/{{$vendor->id}}">
    @method('PUT')
    @csrf
    <div class="row g-3 mb-3">
        <div class="col-lg-6 col-md-12">
            <div class="form-floating">
                <input type="text" class="form-control" name="name" value="{{$vendor->name}}">
                <label>Vendor Name</label>
                <span class="helper-text">Enter the vendor company name</span>
            </div>
        </div>

        <div class="col-lg-6 col-md-12">
            <div class="form-floating">
                <input type="text" class="form-control" name="rep_name" value="{{$vendor->rep_name}}">
                <label>Sales Rep/Contact</label>
                <span class="helper-text">Enter your primary contact's name</span>
            </div>
        </div>
    </div>
    <div class="row  mb-3">
        <div class="col-lg-6 col-md-12">
            <div class="form-floating">
                <input type="text" class="form-control" name="rep_email" value="{{$vendor->rep_email}}">
                <label>Sales Rep Email</label>
                <span class="helper-text">Enter the sales rep's email.</span>
            </div>
        </div>

        <div class="col-lg-6 col-md-12">
            <div class="form-floating">
                <input type="text" class="form-control" name="rep_phone" value="{{$vendor->rep_phone}}">
                <label>Sales Rep Phone Number</label>
                <span class="helper-text">Enter the sales rep's phone number.</span>
            </div>
        </div>

    </div>
    <div class="row mb-3">
        <div class="col-lg-6 col-md-12">
            <div class="form-floating">
                <input type="text" class="form-control" name="order_email" value="{{$vendor->order_email}}">
                <label>E-mail Address (new orders)</label>
                <span class="helper-text">E-mail address for sending hardware orders.</span>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 mt-3">
            <a class="confirm text-danger" data-message="Are you sure you want to remove this vendor?"
               data-method="DELETE" href="/admin/vendors/{{$vendor->id}}"><i class="fa fa-trash"></i> Remove {{$vendor->name}}</a>
            <button type="submit" class="btn btn-primary ladda pull-right">
                <i class="fa fa-save"></i> Save Vendor
            </button>

        </div>


    </div>

</form>
