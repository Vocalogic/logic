@extends('layouts.admin', ['title' => $term->name])


@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">{{$term->name}}</h1>
            <small class="text-muted">Manage your Terms of Service for different types of accounts</small>
        </div>

    </div> <!-- .row end -->

@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <form method="post" action="/admin/terms/{{$term->id}}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-lg-12">
                        <textarea name="body" class="tinymce">{!! $term->body !!}</textarea>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-primary pull-right ladda" data-style="zoom-out">
                                <i class="fa fa-save"></i> Save Service Terms
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
