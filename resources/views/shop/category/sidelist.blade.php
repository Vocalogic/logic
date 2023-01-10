<div class="left-box wow fadeInUp">
    <div class="shop-left-sidebar">
        <div class="back-button">
            <h3><i class="fa-solid fa-arrow-left"></i> Back</h3>
        </div>


        <div class="accordion custome-accordion" id="accordionExample">

            @foreach($category->tagCategories()->where('filter_cat', true)->get() as $filter)

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="true"
                                aria-controls="collapseOne">
                            <span>{{$filter->name}}</span>
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show"
                         aria-labelledby="headingOne">
                        <div class="accordion-body">


                            <ul class="category-list custom-padding custom-height">
                                @foreach($filter->tags as $tag)
                                    <li>
                                        <div class="form-check ps-0 m-0 category-list-box">
                                            <input class="checkbox_animated" type="checkbox" name="f_{{$tag->id}}" id="f_{{$tag->id}}" wire:click="toggleFilter({{$tag->id}})" {{$this->isChecked($tag) ? "checked" : null}}>
                                            <label class="form-check-label" for="f_{{$tag->id}}">
                                                <span class="name">{{$tag->name}}</span>
                                                <span class="number">({{$tag->count}})</span>
                                            </label>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
