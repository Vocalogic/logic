<ul class="nav nav-tabs custom-nav" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="pending-tab" data-bs-toggle="tab"
                data-bs-target="#pending" type="button" role="tab"
                aria-controls="settings" aria-selected="true">Pending Commissions
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="batches-tab" data-bs-toggle="tab"
                data-bs-target="#batches" type="button" role="tab" aria-controls="info"
                aria-selected="false">Batches
        </button>
    </li>


</ul>


<div class="tab-content custom-tab" id="myTabContent">

    <div class="tab-pane fade show active mt-5" id="pending" role="tabpanel"
         aria-labelledby="pending-tab">

        @include('shop.sales.commissions.pending')

    </div>

    <div class="tab-pane fade mt-5" id="batches" role="tabpanel"
         aria-labelledby="batches-tab">

        @include('shop.sales.commissions.batches')
    </div>


</div>
