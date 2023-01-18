<p>
    You can import a list of accounts to Logic by uploading a CSV. These accounts will be marked as
    active and their administrative users created to manage the account. You will need to send a
    password reset request to each user individually.
</p>
<p>
    For a guide of how you should format your accounts, please <a href="/account_import.csv">download a sample csv</a> to
    see the appropriate headers required. Your CSV should use quotes for each field and separated by commas to
    be validated.
</p>
<form class="mt-3" method="POST" action="/admin/accounts/import/csv" enctype="multipart/form-data">
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
        <input type="submit" class="btn btn-{{bm()}}primary" value="Process CSV">
    </div>
</form>


<script>
    $('.drop').dropify();
</script>
