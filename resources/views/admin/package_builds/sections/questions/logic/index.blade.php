@extends('layouts.admin', ['title' => 'Manage Logic', 'crumbs' => [
     "/admin/package_builds" => "Package Builds",
     "/admin/package_builds/$build->id/sections" => $build->name,
     "/admin/package_builds/$build->id/sections/$section->id/questions" => $section->name,
     "Manage Logic"
]])

@section('content')
    <div class="row mb-3">
        <div class="col-lg-12">
            <h5 class="card-title">{{$question->question}}</h5>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-2">
            <a class="w-100 btn btn-block btn-primary live" data-title="New Logic Operation"
               href="/admin/package_builds/{{$build->id}}/sections/{{$section->id}}/questions/{{$question->id}}/logics/create">
                <i class="fa fa-plus"></i> New Logic Operation
            </a>
        </div>

        <div class="col-lg-10">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Add Item</th>
                    <th>If</th>
                    <th>Qty</th>
                </tr>
                </thead>
                <tbody>
                @foreach($question->logics as $logic)
                    <tr>
                        <td>
                            <a class="live" data-title="Edit Logic"
                               href="/admin/package_builds/{{$build->id}}/sections/{{$section->id}}/questions/{{$question->id}}/logics/{{$logic->id}}">
                                @if($logic->addedItem)
                                    {{$logic->addedItem->name}}
                                @else
                                    <b>Addon To: </b> {{$logic->addon->addon->item->name}} <br/>{{$logic->addon->addon->name}} - {{$logic->addon->name}}
                                @endif
                            </a>
                        </td>
                        <td>{{$logic->answer_equates}} {{$logic->answer}}</td>
                        <td>{{$logic->qty_from_answer ? "From Answer" : $logic->qty}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
