

<div class="row g-sm-4 g-3 row-cols-xxl-4 row-cols-xl-3 row-cols-lg-2 row-cols-md-3 row-cols-2 product-list-section">
    @foreach($items as $item)
        @include('shop.category.single', ['item' => $item])
    @endforeach
</div>

<nav class="custome-pagination">
    <ul class="pagination justify-content-center">
        <li class="page-item {{$this->page == 1 ? "disabled" : null}}">
            <a class="page-link" href="#" wire:click="backPage" tabindex="-1" aria-disabled="true">
                <i class="fa-solid fa-angles-left"></i>
            </a>
        </li>
        @foreach(range(1, $this->pages) as $i)
        <li class="page-item {{$this->page == $i ? "active" : null}}">
            <a class="page-link" wire:click="setPage({{$i}})" href="javascript:void(0)">{{$i}}</a>
        </li>
        @endforeach

        <li class="page-item {{$this->page == $this->pages ? "disabled" : null}}">
            <a class="page-link" wire:click="forwardPage" href="#">
                <i class="fa-solid fa-angles-right"></i>
            </a>
        </li>
    </ul>
</nav>
