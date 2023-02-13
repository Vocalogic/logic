<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    Frequently Asked Questions
                </h5>
                <p class="card-text">
                    When customers are browsing your products and services, there may be frequently asked questions
                    that you can provide answers to. These are not required but can be helpful.
                </p>


                <a class="live btn btn-primary" data-title="Create new FAQ"
                   href="/admin/category/{{$cat->id}}/items/{{$item->id}}/faqs/create">
                    <i class="fa fa-plus"></i> Create new FAQ
                </a>
                @if(setting('quotes.openai'))
                    <a class="btn btn-secondary confirm"
                       data-message="WARNING! This will remove any existing frequently asked questions and replace with
                       information obtained from OpenAI. Proceed with Caution! "
                       data-method="GET"
                       data-title="Overwrite with AI?"
                       data-loading="Querying GPT for Frequently Asked Questions"
                       data-confirm="Continue Using AI"
                       href="/admin/category/{{$item->category->id}}/items/{{$item->id}}/ai?mode=faq">
                        <i class="fa fa-expand"></i> Generate using AI
                    </a>
                @endif
                <table class="table table-sm mt-3">
                    <thead>
                    <tr>
                        <th>Question</th>
                        <th>Answer</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($item->faqs as $faq)
                        <tr>
                            <td><a class="live" data-title="{{$faq->question}}"
                                   href="/admin/category/{{$cat->id}}/items/{{$item->id}}/faqs/{{$faq->id}}"><i
                                        class="fa fa-edit"></i></a> {{$faq->question}}</td>
                            <td>{{$faq->answer}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <a class="btn btn-primary mt-3 pull-right"
                   href="/admin/category/{{$cat->id}}/items/{{$item->id}}/requirements">
                    <i class="fa fa-save"></i> Save and Continue</a>

            </div>
        </div>
    </div>
</div>
