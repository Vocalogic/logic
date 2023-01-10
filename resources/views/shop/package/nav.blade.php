<ul class="nav nav-pills nav-justified custom-navtab" id="myTab" role="tablist">
    @foreach($steps as $idx => $s)

        <li class="nav-item" role="presentation">
            <div class="nav-link {{$step == $idx ? "active" : null}}" id="shopping-cart" wire:click="setStep({{$idx}})">
                <div class="nav-item-box">
                    <div>

                        <h4>{{$s['title']}}</h4>
                    </div>
                    <lord-icon target=".nav-item" src="https://cdn.lordicon.com/{{$s['icon']}}.json"
                               trigger="loop-on-hover"
                               colors="primary:#121331,secondary:#646e78,tertiary:#0baf9a" class="lord-icon">
                    </lord-icon>
                </div>
            </div>
        </li>
    @endforeach

</ul>
