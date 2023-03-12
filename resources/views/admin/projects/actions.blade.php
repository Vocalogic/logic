<a class="btn btn-outline-info w-100 ladda" data-style="zoom-in"
   href="/admin/projects/{{$project->id}}/download">
    <i class="fa fa-download"></i> Download Project
</a>
@if(!$project->approved_on)
    <a class="btn btn-outline-info w-100 ladda mt-3" data-style="zoom-in"
       href="/admin/projects/{{$project->id}}/msa">
        <i class="fa fa-building"></i> Edit MSA
    </a>
@endif

<a class="btn btn-outline-success w-100 confirm mt-3" href="/admin/projects/{{$project->id}}/send"
   data-method="GET"
   data-message="Are you sure you want to send this project to the customer for review?">
    <i class="fa fa-send"></i> Send to Customer
</a>
