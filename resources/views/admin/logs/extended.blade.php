@extends('layouts.admin', ['title' => "Extended Log View for {$modelName} #{$entity->id}", 'crumbs' => [
     "{$modelName} Logs #{$entity->id}",
]])

@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">Extended Log View for {{$modelName}} #{{$entity->id}}</h1>
        </div>
    </div> <!-- .row end -->

@endsection
@section('content')
    <div class="row">
        <div class="col-2">
            <div class="card">
                <h6 class="card-title mb-3 pt-2 text-center fs-6">Filter by date</h6>

                <form action="" class="mb-2">
                    <fieldset class="form-icon-group left-icon position-relative">
                        <input
                            type="text"
                            class="form-control"
                            name="start_date"
                            placeholder="Start date"
                            data-role="datepicker"
                            data-provide="datepicker"
                            data-date-format="mm/dd/yyyy"
                            value="{{ old('start_date') }}"
                        >
                        <div class="form-icon position-absolute">
                            <i class="fa fa-calendar"></i>
                        </div>
                    </fieldset>

                    <fieldset class="form-icon-group left-icon position-relative mt-2">
                        <input
                            type="text"
                            class="form-control"
                            name="end_date"
                            placeholder="End date"
                            data-role="datepicker"
                            data-provide="datepicker"
                            data-date-format="mm/dd/yyyy"
                            value="{{ old('end_date') }}"
                        >
                        <div class="form-icon position-absolute">
                            <i class="fa fa-calendar"></i>
                        </div>
                    </fieldset>

                    <fieldset class="mt-2">
                        <button class="btn btn-primary pull-right d-block w-100" type="submit">
                            Search
                        </button>
                    </fieldset>
                </form>
            </div>
        </div>
        <div class="col-10">
            @livewire('iterators.log-iterator-component')
        </div>
    </div>
@endsection


