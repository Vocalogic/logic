@extends('layouts.shop.main', ['title' => $project->name, 'crumbs' => [
     "/shop" => "Home",
     $lead->company
]])

@section('content')
    <section class="user-dashboard-section section-b-space">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-xxl-3 col-lg-4 col-xs-12">
                    @include('shop.presales.menu')
                    @include('shop.presales.projects.stats')


                </div>


                <div class="col-xxl-9 col-lg-4 col-xs-12">
                    <div style="text-align:center">
                        <h4 class="card-title">{{$project->name}}</h4>
                        <h5>{{$project->description}}</h5>
                    </div>
                    <h4 class="bold">Statement of Work</h4>
                    <p class="card-text">
                        {!! $project->summary !!}
                    </p>

                    <h4 class="bold">Actionable Items</h4>
                    @foreach($project->categories as $category)
                        <div class="card mt-2">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="card-title">{{$category->name}}</h5>
                                <span
                                    class="fs-6">${{moneyFormat($category->totalMin)}} - ${{moneyFormat($category->totalMax)}} </span>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-8 col-xs-12">

                                        <p class="card-text">
                                            {!! $category->description !!}
                                        </p>

                                        @if($category->tasks->count())
                                            <table class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Task</th>
                                                    <th>Est Hours</th>
                                                    <th>Actual Hours</th>
                                                    <th>Assigned</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($category->tasks as $task)
                                                    <tr>
                                                        <td>{{$task->name}}</td>
                                                        <td>{{$task->est_hours_min}} - {{$task->est_hours_max}}</td>
                                                        <td>{{$task->time}}</td>
                                                        <td>{{$task->assigned ? $task->assigned->short : "Unassigned"}}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>

                                        @endif
                                    </div>
                                    <div class="col-lg-4 col-xs-12">
                                        @livewire('admin.thread-component', ['object' => $category])
                                    </div>
                                </div>


                            </div>
                        </div>
                    @endforeach
                </div>


            </div>
        </div>
    </section>

@endsection
