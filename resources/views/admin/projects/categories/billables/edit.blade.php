<div class="sModalArea">
    <form method="POST" action="/admin/projects/{{$project->id}}/categories/{{$category->id}}/items/{{$item->id}}">
        @method("PUT")
        @csrf


        <ul class="nav nav-tabs tab-card" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#nav-pricing"
                                    role="tab">Pricing</a></li>

            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#nav-notes" role="tab">Notes</a></li>
            @if(!$item->bill_item_id)
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#nav-expenses" role="tab">Expense</a></li>
            @endif
        </ul>

        <div class="tab-content mt-4 mb-3">

            <div class="tab-pane fade show active" id="nav-pricing" role="tabpanel">


                <div class="row g-2">
                    <div class="col-md-4 col-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="price"
                                   value="{{moneyFormat($item->price)}}">
                            <label>Price</label>
                            <span class="helper-text">Update item price.</span>
                        </div>
                    </div>
                    <div class="col-md-4 col-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="qty" value="{{$item->qty}}">
                            <label>QTY</label>
                            <span class="helper-text">Update quantity.</span>
                        </div>
                    </div>
                    <div class="col-md-4 col-4">
                        <div class="form-floating">
                            {!! Form::select('bill_type', ['Start' => 'Start', 'During' => 'During', 'End' => 'End'], $item->bill_type ?: 'During', ['class' => 'form-control']) !!}
                            <label>Billing Time</label>
                            <span class="helper-text">When should this item be invoiced?</span>
                        </div>
                    </div>
                </div>
                @if(!$item->bill_item_id)
                <div class="row mt-2">
                    <div class="col-md-12">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="name" value="{{$item->name}}">
                            <label>Custom Item Name</label>
                            <span class="helper-text">Edit custom item name.</span>
                        </div>
                    </div>
                </div>
                @endif

                <div class="row mt-2">
                    <div class="col-md-12">
                        <div class="form-floating">
                            <textarea class="form-control" name="description"
                                      style="height:150px;">{{$item->description}}</textarea>
                            <label>Description</label>
                            <span class="helper-text">Update description of item.</span>
                        </div>
                    </div>
                </div>



            </div>


            <div class="tab-pane fade" id="nav-notes" role="tabpanel">
                <div class="row mt-2">
                    <div class="col-md-12">
                        <div class="form-floating">
                            <textarea class="form-control" name="notes"
                                      style="height: 150px;">{{$item->notes}}</textarea>
                            <label>Notes</label>
                            <span class="helper-text">Enter additional notes on service (i.e. specifics)</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="nav-expenses" role="tabpanel">
                <div class="row g-2">
                    <div class="col-md-12 col-12">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="expense"
                                   value="{{moneyFormat($item->expense)}}">
                            <label>Expense For Item</label>
                            <span class="helper-text">Enter the total expense for this custom line item</span>
                        </div>
                    </div>

                    <div class="col-md-12 col-12">
                        <div class="form-floating">
                             <textarea class="form-control" name="expense_description"
                                       style="height: 150px;">{{$item->expense_description}}</textarea>
                            <label>Expense Description</label>
                            <span class="helper-text">Enter notes on what this expense covers</span>
                        </div>
                    </div>

                </div>


            </div>

        </div>


            <div class="col-lg-12 mt-3">
                <div class="d-flex justify-content-between">

                    <a href="/admin/projects/{{$project->id}}/categories/{{$category->id}}/items/{{$item->id}}"
                       class="confirm text-danger"
                       data-method="DELETE"
                       data-message="Are you sure you want to remove this billable item from {{$category->name}}?">
                        <i class="fa fa-trash"></i> Remove Item
                    </a>

                    <button type="submit" class="btn btn-primary ladda" data-style="zoom-out">
                        <i class="fa fa-save"></i> Update Item
                    </button>
                </div>
            </div>
    </form>


</div>
