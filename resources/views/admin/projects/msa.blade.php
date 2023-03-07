@extends('layouts.admin', ['title' => $project->name . " Master Services Agreement", 'crumbs' => [
     '/admin/projects' => "Projects",
     $project->name . " MSA"
    ],
])

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <form method="POST" action="/admin/projects/{{$project->id}}/msa">
                @csrf
                @method('POST')
                <div class="row">
                    <div class="col-lg-12">
                    <textarea class="tinymce" name="msa">{!! $project->msa !!}

                    </textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-primary ladda pull-right mt-3">
                            <i class="fa fa-save"></i> Save MSA
                        </button>
                    </div>
                </div>

            </form>
        </div>

    </div>
@endsection
