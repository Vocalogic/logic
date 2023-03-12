@extends('layouts.shop.main', ['title' => $project->name . " - Execute", 'crumbs' => [
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

                <div class="col-lg-9 col-xs-12">
                    <div style="text-align:center;">
                        <h4><b>{{$project->name}}</b></h4>
                        <h5 class="mt-3">{{$project->description}}</h5>

                    </div>

                    <div class="mt-4">
                        {!! $project->msa !!}
                    </div>

                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Deliverables</h4>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Deliverable</th>
                                    <th>Dates</th>
                                    <th>Est. Cost</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($project->categories as $category)
                                    <tr>
                                        <td>
                                            <b>{{$category->name}}</b><br/>
                                            <small class="text-muted">{{$category->description}}</small>
                                        </td>
                                        <td>
                                            {{$category->start_date ? $category->start_date->format("m/d/y") : "Undefined"}}
                                            -
                                            {{$category->end_date ? $category->end_date->format("m/d/y") : "Undefined" }}
                                        </td>
                                        <td>
                                            ${{moneyFormat($category->totalMin)}} - ${{moneyFormat($category->totalMax)}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>


                            <h4>Signature and Execution</h4>
                            <p>
                                Please sign below to execute this agreement and to begin your project.
                            </p>

                            <div style="border: 1px #000 solid; background: #fff;">
                                <div id="signature-pad" class="signature-pad">
                                    <div class="signature-pad--body">
                                        <canvas></canvas>
                                    </div>
                                </div>
                            </div>

                            <form method="POST" class="mt-4" action="/shop/presales/{{$lead->hash}}/projects/{{$project->hash}}/execute">
                                @csrf
                                @method('POST')
                                <x-form-input name="name" label="Enter your name for signing" icon="user">
                                    Enter your name on the agreement.
                                </x-form-input>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <button type="submit" name="submit" class="btn bg-primary text-white">
                                            <i class="fa fa-save"></i> &nbsp; Save Signature and Execute
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>


                </div>


            </div>
        </div>
    </section>
@endsection
