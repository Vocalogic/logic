@extends('layouts.shop.main', ['title' => $project->name, 'crumbs' => [
     "/shop" => "Home",
     "/shop/account" => "My Account",
     "/shop/account/projects" => "Projects",
     $project->name

]])

@section('content')
    <section class="user-dashboard-section section-b-space">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-xxl-3 col-lg-4">
                    @include('shop.account.menu')
                </div>

                <div class="col-xxl-9 col-lg-8">
                    <div class="dashboard-right-sidebar">

                        <div class="dashboard-profile">
                            <div class="title">
                                <h2>{{$project->name}}</h2>
                                <span class="title-leaf">
                                            <svg class="icon-width bg-gray">
                                                <use xlink:href="/ec/assets/svg/leaf.svg#leaf"></use>
                                            </svg>
                                        </span>
                            </div>
                        </div>

                        <ul class="nav nav-tabs shop-nav-tabs" it="myTab" role="tablist">
                            @foreach($project->categories as $category)
                                <li class="nav-item">
                                    <a class="nav-link {{$loop->first ? "active" : null}}" data-bs-toggle="tab"
                                       data-bs-target="#cat-{{$category->id}}"
                                       href="#cat-{{$category->id}}">{{$category->name}}</a>
                                </li>
                            @endforeach
                        </ul>

                        <div class="tab-content" id="myTabContent">
                            @foreach($project->categories as $category)
                                <div class="tab-pane fade {{$loop->first ? "show active" : null}}"
                                     id="cat-{{$category->id}}" role="tabpanel">
                                    <div class="row mt-2">
                                        <div class="col-lg-12">
                                            <table class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Item</th>
                                                    <th>Est. Hours</th>
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
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="card">

                                                <div class="card-body">
                                                    @livewire('admin.thread-component', ['object' => $category])
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            @endforeach
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
