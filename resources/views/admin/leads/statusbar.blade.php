<a class="btn btn-primary w-100 btn-block  mt-3" href="#newLead" data-bs-toggle="modal"
   type="button"><i class="fa fa-plus"></i> New Lead
</a>


<div class="card mt-4 mb-3">
        <h6 class="card-title mb-3 pt-2 text-center fs-6">Status</h6>
        <ul class="list-group list-group-custom">
            <li class="list-group-item"><a class="color-600" href="/admin/leads">All Active Leads ({{\App\Models\Lead::where('active', true)->count()}})</a></li>

        @foreach(\App\Models\LeadStatus::with('leads')->orderBy('name')->get() as $status)
            <li class="list-group-item"><a class="color-600" href="/admin/leads?status={{$status->id}}">{{$status->name}} ({{$status->leads->count()}})</a></li>
            @endforeach
        </ul>

</div>

<a class="btn btn-{{bm()}}secondary live w-100 btn-block mt-2" href="/admin/leads/import/csv"
   data-title="Import Leads into Logic">
    <i class="fa fa-recycle"></i> Import Leads
</a>
