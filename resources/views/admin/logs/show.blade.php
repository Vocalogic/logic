<div class="mb-2" style="text-align: right;">
    <a href="{{ request()->url() }}/extended">Extended View</a>
</div>

<div class="card">
    <div class="card-body d-flex align-items-start p-0">
        <ul class="nav nav-pills custom-horizontal me-2" role="tablist">
            @foreach(\App\Enums\Core\LogSeverity::cases() as $case)
                @if($loop->first)
                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#case-{{$case->value}}"
                                            role="tab">{{$case->getShort()}}</a></li>
                @else
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#case-{{$case->value}}"
                                            role="tab">{{$case->getShort()}}</a></li>
                @endif
            @endforeach
        </ul>

        <div class="tab-content">
            @foreach(\App\Enums\Core\LogSeverity::cases() as $case)
                @if($loop->first)
                    <div class="tab-pane fade show active" id="case-{{$case->value}}" role="tabpanel">
                        @include('admin.logs.list', ['level' => $case->value])
                    </div>
                @else
                    <div class="tab-pane fade" id="case-{{$case->value}}" role="tabpanel">
                        @include('admin.logs.list', ['level' => $case->value])
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
