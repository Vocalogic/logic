<div class="col-xxl-4 col-lg-4 col-md-12 col-sm-12">
    <a href="/shop/account/projects/{{$project->hash}}">
        <div class="totle-contain">
            <img src="/ec/assets/images/svg/pending.svg"
                 class="img-1 blur-up lazyloaded" alt="">
            <img src="/ec/assets/images/svg/pending.svg" class="blur-up lazyloaded"
                 alt="">
            <div class="totle-detail">
                <h5>Project #{{$project->id}}</h5>
                <h3>
                    ${{moneyFormat($project->totalMax)}}
                </h3>
                <h6><small class="text-muted">{{$project->name}}</small></h6>
            </div>
        </div>
    </a>
</div>
