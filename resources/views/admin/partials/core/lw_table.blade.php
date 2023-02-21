<div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12 d-flex">
                    <fieldset class="form-icon-group left-icon position-relative">
                        <input class="form-control" wire:model="search" placeholder="Search...">
                        <div class="form-icon position-absolute">
                            <i class="fa fa-search"></i>
                        </div>
                    </fieldset>

                </div>

                <div class="col-lg-12">
                    <table class="table align-middle table-striped table-sm">
                        <thead>
                        <tr>
                            @foreach($headers as $header => $data)
                                <th>
                                    @if(!empty($data))
                                        <a href="#" wire:click="sort('{{$data[0]}}')">{{$header}}</a>
                                    @else
                                        {{$header}}
                                    @endif
                                </th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($collection as $row)
                            @include($entity, ['obj' => $row])
                        @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end">
                        <div>
                            <div class="row">
                                <div class="col">
                                    <label class="form-label">
                                        Results Per Page
                                    </label>
                                </div>
                                <div class="col">
                                    <select wire:model="rowsPerPage" class="form-control flex-lg-shrink-1">
                                        <option value="15">15</option>
                                        <option value="24">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                            </div>


                        </div>
                        <div>
                            <nav aria-label="pagination">
                                <ul class="pagination">

                                    <li class="page-item {{$prevDisabled ? "disabled" : ""}}">
                                        <a class="page-link" wire:click="prev" href="#" tabindex="-1"
                                           aria-disabled="true">Previous</a>
                                    </li>
                                    @foreach(range(1, $maxPages) as $page)

                                        <li class="page-item {{$page == $activePage ? "active" : ""}}"
                                            aria-current="page">
                                            <a class="page-link" wire:click="toPage({{$page}})" href="#">{{$page}}</a>
                                        </li>
                                    @endforeach

                                    <li class="page-item {{$nextDisabled ? "disabled" : ""}}">
                                        <a class="page-link" href="#" wire:click="next">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
