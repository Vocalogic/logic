<div class="row">

    <div class="col-lg-12 mb-3">
        <div class="card bg-gray border-0">
            <div class="card-content">
                {!! _markdown($steps[$step]['description']) !!}
            </div>

        </div>
    </div>

    <div class="col-lg-12 mt-2">
        @if(count($questions))
            @foreach($questions as $q)
                @if($this->shouldRender($q) && ($q['type'] != 'multi' && $q['type'] != 'product'))
                <div class="row">
                    <div class="col-lg-12 mb-5">
                        <div class="form-floating theme-form-floating">
                            @include('shop.package.render_input', ['q' => $q])
                            <label for="pname">{{$q['question']}}</label>
                        </div>
                    </div>
                </div>
                @endif
                @if($this->shouldRender($q) && $q['type'] == 'multi')
                    <div class="row">
                        <div class="col-lg-12 mb-5">
                            @include('shop.package.render_input', ['q' => $q])

                        </div>
                    </div>
                @endif

                    @if($this->shouldRender($q) && $q['type'] == 'product')
                        <div class="row">
                            <div class="col-lg-12 mb-5">
                                @include('shop.package.render_input', ['q' => $q])
                            </div>
                        </div>
                    @endif


            @endforeach



        @endif
    </div>
</div>
