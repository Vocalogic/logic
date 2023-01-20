@extends('layouts.admin', ['title' => "Active Quotes", 'crumbs' => [
     "Active Quotes"
]])

@section('content')
    <div class="row">
        @include('admin.quotes.graphs')
        <div class="col-lg-12">
            @if(\App\Models\Quote::where('archived', false)->count() == 0)
                <div class="card mt-3">
                    <div class="card-body text-center">
                        <img src="/assets/images/no-data.svg" class="w120" alt="No Data">
                        <div class="mt-4 mb-3">
                            <span class="text-muted">No quotes found. Quotes are created from leads or accounts.</span>
                        </div>
                    </div>
                </div>
            @else
                @include('admin.quotes.list')
            @endif
        </div>
    </div>
@endsection
