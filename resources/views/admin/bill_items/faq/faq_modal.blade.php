<form method="post" action="/admin/category/{{$cat->id}}/items/{{$item->id}}/faqs{{$faq->id ? "/$faq->id" : null}}">
    @csrf
    @method($faq->id ? "PUT" : "POST")

    <div class="row mt-2">
        <div class="col-lg-12 col-md-12">
            <div class="form-floating">
                <input type="text" class="form-control" name="question" value="{{$faq->question}}">
                <label>Question:</label>
                <span class="helper-text">Enter a frequently asked question about this item</span>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 mt-3">
            <div class="form-floating">
                <textarea class="form-control" name="answer" style="height:200px;">{!! $faq->answer !!}</textarea>
                <label>Answer:</label>
                <span class="helper-text">Enter the answer to this question</span>
            </div>
        </div>


        <div class="col-lg-12 col-md-12 mt-3">
            <input type="submit" class="btn btn-primary" name="submit" value="Save">
            @if($faq->id)
                <a class="pull-right confirm btn btn-danger" data-message="Are you sure you want to delete this FAQ?"
                   data-method="DELETE"
                   href="/admin/category/{{$cat->id}}/items/{{$item->id}}/faqs/{{$faq->id}}"><i class="fa fa-trash"></i>
                    Delete FAQ</a>
            @endif
        </div>

    </div>

</form>

