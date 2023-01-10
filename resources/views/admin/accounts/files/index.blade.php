<div class="row">
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body">
                <a href="#uploadFileModal" data-bs-toggle="modal" class="btn bg-secondary text-light mb-3 w-100"><i
                        class="fa fa-upload"></i> Upload File</a>

                <ul class="nav nav-tabs menu-list list-unstyled mb-0 border-0" role="tablist">
                    @foreach(\App\Models\FileCategory::orderBy('name')->get() as $cat)
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="tab" data-bs-target="#c{{$cat->id}}"
                           role="tab">
                            <span>{{$cat->name}}</span>
                            <span class="badge bg-light text-dark ms-2 ms-auto">
                                {{$account->getFileCount($cat)}}
                            </span>
                        </a>
                    </li>
                @endforeach


                </ul>
            </div>
        </div>


    </div>
    <div class="col-lg-9">
        <div class="tab-content">
        @foreach(\App\Models\FileCategory::orderBy('name')->get() as $cat)
            <div class="tab-pane fade" id="c{{$cat->id}}">
                @include('admin.accounts.files.list', ['cat' => $cat])
            </div>
        @endforeach
        </div>
    </div>
</div>


<div class="modal fade" id="uploadFileModal" tabindex="-2" role="dialog" aria-labelledby="liveModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="px-xl-4 modal-header">
                <h5 class="modal-title">Upload File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <p>Upload images, audio or documents, internal documentation and more for {{$account->name}}. <strong>NOTE:</strong> If your
                    file needs to be accessible via the internet, make sure you set it as public.</p>

                <form method="POST" action="/admin/accounts/{{$account->id}}/files" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="description">
                                <label>Description</label>
                                <span class="helper-text">Enter a short description/name of file</span>
                            </div>

                            <div class="form-floating">
                                {!! Form::select('file_category_id', \App\Models\FileCategory::orderBy('name')->pluck("name", "id"), null, ['class' => 'form-control']) !!}
                                <label>File Category</label>
                                <span class="helper-text">Select category for file.</span>
                            </div>


                            <div class="form-floating">
                                {!! Form::select('public', [0 => 'No', 1 => 'Yes'], null, ['class' => 'form-control']) !!}
                                <label>Make file Public?</label>
                                <span class="helper-text">If file should be accessed via the internet, select yes.</span>
                            </div>

                        </div>

                    </div>
                    <input type="file" name="uploaded" class="drop"/>
                    <input type="submit" name="submit" class="btn btn-sm btn-primary mt-3 wait" data-anchor=".modal" value="Upload File">
                </form>

            </div>
        </div>
    </div>
</div>
