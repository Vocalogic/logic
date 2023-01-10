@extends('layouts.admin', ['title' => 'Discovery Builder', 'crumbs' => [
     "Discovery",
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Discovery Builder</h1>
            <small class="text-muted">Add questions to ask your potential customers based on the lead type.</small>
        </div>
    </div> <!-- .row end -->

@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    @foreach(\App\Models\LeadType::all() as $type)
                        <p class="card-title">{{$type->name}}</p>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Question</th>
                                <th>Answer Type</th>
                                <th>Options</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($type->questions as $question)
                                <tr>
                                    <td>
                                        <a class="xedit" data-pk="{{$question->id}}" data-url="/admin/discovery/{{$question->id}}/live"
                                           data-title="Enter Question" data-field="question">
                                            {{$question->question}}</a> <a class="confirm" data-message="Are you sure you want to delete this question?"
                                                                           data-method="DELETE" href="/admin/discovery/{{$question->id}}"><i class="fa fa-trash"></i>
                                        </a>
                                            <br/>
                                        <small class="text-muted">
                                            <a class="xedit" data-pk="{{$question->id}}" data-url="/admin/discovery/{{$question->id}}/live"
                                               data-title="Enter Help" data-type='textarea' data-field="help">{{$question->help ?: "Add Help"}}</a>
                                        </small>
                                    </td>
                                    <td>
                                        <a class="xedit" data-pk="{{$question->id}}" data-url="/admin/discovery/{{$question->id}}/live"
                                           data-title="Select Type" data-type="select" data-source="['Small Text', 'Large Text', 'Dropdown']" data-field="type">
                                            {{$question->type}}</a>

                                    </td>
                                    <td>
                                        @if($question->type == 'Dropdown')
                                            <a class="xedit" data-pk="{{$question->id}}" data-url="/admin/discovery/{{$question->id}}/live"
                                               data-title="Enter Options" data-field="opts">
                                                {{$question->opts}}</a>
                                        @endif
                                        &nbsp;
                                    </td>
                                </tr>
                            @endforeach
                                <form method="POST" action="/admin/discovery/create/{{$type->id}}">
                                    @method('POST')
                                    @csrf
                                    <tr>
                                        <td><input type="text" class="form-control" name="question"></td>
                                        <td><select name="type" class="form-control">
                                                <option value="Small Text">Small Text</option>
                                                <option value="Large Text">Large Text</option>
                                                <option value="Dropdown">Dropdown</option>
                                            </select>
                                        </td>
                                        <td><input type="submit" class="btn btn-primary" value="Add"></td>
                                    </tr>
                                </form>
                            </tbody>
                        </table>


                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
