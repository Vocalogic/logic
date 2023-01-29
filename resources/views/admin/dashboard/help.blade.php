<div class="row">
    <div class="col-lg-12 mb-3">
        <h4>Getting Started</h4>
        <p class="card-text">The items listed below should be completed to properly brand
            and ensure your customers are receiving emails. If you need help you can
            refer to the <a href="https://logic.readme.io/docs">documentation</a> or watch
            a <a href="https://www.youtube.com/watch?v=pYMHh8H4J_k">youtube video</a> to
            understand the different settings provided.
        </p>
    </div>
    @foreach(\App\Models\Account::gettingStarted() as $item)
        <div class="col-lg-3">
            @include('admin.partials.help', ['icon' => $item->icon, 'title' => $item->title, 'body' => $item->body, 'target' => $item->target, 'action' => $item->action])
        </div>
    @endforeach
</div>
