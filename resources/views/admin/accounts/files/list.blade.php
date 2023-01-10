<div class="row g-2 row-deck">
    @foreach(\App\Models\LOFile::where('account_id', $account->id)->where('file_category_id', $cat->id)->get() as $file)
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="card p-3">
            <div class="option-9 position-absolute text-light"></div>
            <i class="fa {{$file->category->type->getIcon()}} fa-2x"></i>
            <div class="mt-3">
                <h5>{{$file->filename}}
                <a href="/admin/accounts/{{$account->id}}/files/{{$file->id}}" class="confirm"
                   data-message="Are you sure you want to remove this file permanently?"
                   data-method="DELETE"><i class="fa fa-trash"></i></a>
                </h5>
                <p>{{$file->description}}</p>
                <div class="d-flex text-muted flex-wrap justify-content-between small text-uppercase">Public: <span>{{$file->auth_required ? "No" : "Yes"}}</span></div>
                <div class="d-flex text-muted flex-wrap justify-content-between small text-uppercase">Size: <span>{{formatBytes($file->filesize)}}</span></div>
                <div class="d-flex text-muted flex-wrap justify-content-between small text-uppercase">Download: <span><a href="{{_file($file->id)->relative}}"><i class="fa fa-download"></i></a></span></div>

                <p class="mt-2">
                @if(!$file->auth_required)
                    <b>Public URL: </b> <a target="_blank" href="{{_file($file->id)->url}}">{{_file($file->id)->url}}</a>
                @endif
                </p>
            </div>

        </div>
    </div>
    @endforeach
</div>
