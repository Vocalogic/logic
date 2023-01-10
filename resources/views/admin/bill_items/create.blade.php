@extends('layouts.admin', ['title' => "Modify $type", 'layout' => 2,
'crumbs' => [
    "/admin/category/{$cat->id}/items" => $cat->name,
    $item->name ?: "New " .$type
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">{{$item->name ?: "Create new $type"}}</h1>
            <small class="text-muted">{{$item->description ?: null}}</small>
        </div>
    </div> <!-- .row end -->

@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">

        </div>
    </div>
@endsection
@section('right')
    @include('admin.bill_items.right')
@endsection
