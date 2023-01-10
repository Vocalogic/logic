<p>
    You can import a list of leads to Logic by uploading a CSV of the leads. They will be assigned the
    status you give them below and you can select if you want them to be deactivated by default.
</p>
<p>
    For a guide of how you should format your leads, please <a href="/lead_import.csv">download a sample csv</a> to
    see the appropriate headers required. Your CSV should use quotes for each field and separated by commas to
    be validated.
</p>
<form class="mt-3" method="POST" action="/admin/leads/import/csv" enctype="multipart/form-data">
    @csrf
    @method('POST')
    <div class="row">
        <div class="col-lg-12">
            <div class="form-floating mt-2">
                <input type="file" name="import_file" class="drop"
                       data-default-file=""/>
                <label>Upload CSV</label>
                <span class="helper-text">Select a CSV file for upload</span>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-lg-4">
            <div class="form-floating mt-2">
                {!! Form::select('lead_status_id', \App\Models\LeadStatus::getSelectable(true, true), null, ['class' => 'form-select']) !!}
                <label>Select Status for Leads</label>
                <span class="helper-text">Select the lead assignment</span>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-floating mt-2">
                {!! Form::select('active', [0 => 'Not Active', 1 => "Active"], null, ['class' => 'form-select']) !!}
                <label>Select if Leads are Active</label>
                <span class="helper-text"><b>Not Active</b> means Leads will not show in the main area.</span>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-floating mt-2">
                {!! Form::select('lead_type_id', \App\Models\LeadType::getSelectable(), null, ['class' => 'form-select']) !!}
                <label>Select Type of Lead</label>
                <span class="helper-text">Select which classification for leads.</span>
            </div>
        </div>

    </div>
    <div class="row mt-2">
        <input type="submit" class="btn btn-{{bm()}}primary" value="Process CSV">
    </div>
</form>


<script>
    $('.drop').dropify();
</script>
