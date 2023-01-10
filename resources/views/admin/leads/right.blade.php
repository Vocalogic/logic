<div class="rightbar card">
    <div class="card-header bg-transparent">
        <h6 class="card-title btn-right mb-0">
            <a href="#" title="Settings">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                     class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                    <path class="fill-muted"
                          d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                </svg>
            </a>
            {{$lead->name ?: "New Lead"}}
        </h6>
        <div class="dropdown morphing scale-left">
            <a href="#" class="more-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i
                    class="fa fa-ellipsis-h"></i></a>
            <ul class="dropdown-menu shadow border-0 p-2">

                <li><a class="dropdown-item" href="/admin/leads">Cancel</a></li>
            </ul>
        </div>
    </div>

    <div class="card">
        <div class="card-body">



        </div>
    </div>

</div>
