@extends('layouts.admin', ['title' => $account->name, 'crumbs' => [
    '/admin/accounts' => "Accounts",
    "/admin/accounts/$account->id" => $account->name,
    'Files'

], 'log' => $account->logLink])
@section('content')
    <div class="row">
        <div class="col-lg-2 col-xs-12">
            @include('admin.accounts.submenu')
        </div>

        <div class="col-lg-10 col-xs-12">

            <div class="row">
                <div class="col-lg-9">
                    <div class="tab-content">
                        @foreach(\App\Models\FileCategory::orderBy('name')->get() as $cat)
                            <div class="tab-pane fade {{$loop->first ? "active show" : null}}" id="c{{$cat->id}}">
                                @include('admin.accounts.files.list', ['cat' => $cat])
                            </div>
                        @endforeach
                    </div>
                </div>


                <div class="col-lg-3">
                    <a href="#uploadFileModal" data-bs-toggle="modal"
                       class="btn btn-primary mb-3 w-100"><i
                            class="fa fa-upload"></i> Upload File</a>
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-tabs menu-list list-unstyled mb-0 border-0" role="tablist">
                                @foreach(\App\Models\FileCategory::orderBy('name')->get() as $cat)
                                    <li class="nav-item {{$loop->first ? "active" : null}}">
                                        <a class="nav-link" href="#" data-bs-toggle="tab"
                                           data-bs-target="#c{{$cat->id}}"
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

            </div>

            <x-modal name="uploadFileModal" title="Upload File">
                <p>Upload images, audio or documents, internal documentation and more for {{$account->name}}
                    . <strong>NOTE:</strong> If your
                    file needs to be accessible via the internet, make sure you set it as public.</p>

                <form method="POST" action="/admin/accounts/{{$account->id}}/files"
                      enctype="multipart/form-data" class="uploadForm">
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
                                <span
                                    class="helper-text">If file should be accessed via the internet, select yes.</span>
                            </div>

                        </div>

                    </div>
                    <input type="file" name="uploaded" class="drop"/>
                    <button type="submit" class="btn btn-primary mt-3 ladda pull-right" data-style="zoom-out">
                           <i class="fa fa-file-o"></i> Upload File
                    </button>
                </form>
            </x-modal>


        </div>
    </div>
@endsection
