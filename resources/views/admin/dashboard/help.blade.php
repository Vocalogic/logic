<div class="col-lg-12">
    <h4 class="card-title">Finish your setup below...</h4>

</div>
@foreach(\App\Models\Account::gettingStarted() as $item)
    <div class="col-lg-3">
        @include('admin.partials.help', ['icon' => $item->icon, 'title' => $item->title, 'body' => $item->body, 'target' => $item->target, 'action' => $item->action])
    </div>
@endforeach
