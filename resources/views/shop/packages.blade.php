<section class="faq-breadscrumb pt-0">
    <div class="container-fluid-lg">
        <div class="row">
            <div class="col-12">
                <div class="breadscrumb-contain">
                    <h2>Build Your Service Quote</h2>
                    <p>Select from the options below to instantly build your quote based on a few questions. After you
                        are finished, you will be able to view your cart without entering any personal information; or
                        enter your email address to download a quote with exact pricing for your business.</p>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="faq-contain">
    <div class="container">
        <div class="row">
            @foreach(\App\Models\PackageBuild::where('active', true)->get() as $build)
                <div class="col-xxl-3 col-md-6">
                    <div class="faq-top-box">
                        <div class="faq-box-icon">
                            <img src="/ec/assets/images/inner-page/faq/start.png" class="blur-up lazyloaded"
                                 alt="">
                        </div>

                        <div class="faq-box-contain">
                            <h3><a href="/shop/build/{{$build->slug}}">{{$build->name}}</a></h3>
                            <p style="-webkit-line-clamp: 5;">{!! $build->description !!}
                            </p>
                            <br/>
                            <a href="/shop/build/{{$build->slug}}/">Build Quote <i class="fa fa-fast-forward"></i></a>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
