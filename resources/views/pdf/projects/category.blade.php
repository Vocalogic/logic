@foreach($project->categories as $category)
    <div class="row">
        <div class="col-xs-12">
            <h4 style="text-align:center;">{{$category->name}}</h4>
            <h5 style="text-align:center;">{{$category->description}}</h5>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    Tasks to be Completed
                </div>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Task</th>
                        <th>Est. Hours</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($category->tasks as $task)
                        <tr>
                            <td><strong>{{$task->name}}</strong></td>
                            <td>{{$task->est_hours_min}} - {{$task->est_hours_max}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <h4>Task Details</h4>
            <ul>
            @foreach($category->tasks as $task)
                <li><strong>{{$task->name}}</strong>
                    {!! $task->description !!}</li>
            @endforeach
            </ul>
            @if($category->items->count())
             <div class="panel panel-info">
                 <div class="panel-heading">
                     Billable Items for {{$category->name}}
                 </div>
                 <table class="table table-striped">
                     <thead>
                     <tr>
                         <th>Item</th>
                         <th>Price</th>
                         <th>Qty</th>
                         <th>Total</th>
                         <th>Billed</th>
                     </tr>
                     </thead>
                     <tbody>
                     @foreach($category->items as $item)
                         <tr>
                             <td><strong>[{{$item->code}}] {{$item->name}}</strong>
                             <br/>
                                 <small class="text-muted">{{$item->description}}</small>
                             </td>
                             <td>
                                 ${{moneyFormat($item->price)}}
                             </td>
                             <td>{{$item->qty}}</td>
                             <td>${{moneyFormat(bcmul($item->price * $item->qty,1))}}</td>
                             <td>{{$item->bill_type}}</td>
                         </tr>
                     @endforeach
                     </tbody>
                 </table>
             </div>
            @endif



        </div>
    </div>

@if(!$loop->last)
<div class="page-break"></div>
@endif
@endforeach
