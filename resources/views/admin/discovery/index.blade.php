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
                                        <a class="live" data-title="Edit Question" href="/admin/discovery/{{$question->id}}">
                                            {{$question->question}}
                                        </a>
                                        <a class="confirm" data-message="Are you sure you want to delete this question?"
                                                                           data-method="DELETE" href="/admin/discovery/{{$question->id}}"><i class="fa fa-trash"></i>
                                        </a>
                                            <br/>
                                        <small class="text-muted">{{$question->help ?: "Add Help"}}</small>
                                    </td>
                                    <td>{{$question->type}}</td>
                                    <td>
                                        @if($question->type == 'Dropdown')
                                            {{$question->opts}}
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
                                        <td><button type="submit" class="btn btn-primary ladda" data-style="expand-left"><i class="fa fa-plus"></i> Add</button></td>
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
