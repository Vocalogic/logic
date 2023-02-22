@extends('layouts.admin', ['title' => "Cash Flow Report", 'crumbs' => [
     "Cash Flow",
]])

@section('content')
    <div class="row">
        <div class="col-lg-12 mt-2">

            <div class="card">
                <div class="card-body">


                    <h6 class="card-title mb-3">{{$y1}}</h6>

                    <table class="table table-sm" >
                        <thead>
                        <tr>
                            <th>Item</th>
                            <th>Jan</th>
                            <th>Feb</th>
                            <th>Mar</th>
                            <th>Apr</th>
                            <th>May</th>
                            <th>Jun</th>
                            <th>Jul</th>
                            <th>Aug</th>
                            <th>Sep</th>
                            <th>Oct</th>
                            <th>Nov</th>
                            <th>Dec</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Contracted Revenue</td>
                                @foreach(range(1,12) as $r)
                                    <td class="table-{{$data1[$r]->color}}">{{moneyFormat($data1[$r]->contracted,2)}}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td>Uncontracted Revenue</td>
                                @foreach(range(1,12) as $r)
                                    <td class="table-{{$data1[$r]->color}}">{{moneyFormat($data1[$r]->uncontracted,2)}}</td>
                                @endforeach
                            </tr>

                            <tr>
                                <td>Forecasted Contracted</td>
                                @foreach(range(1,12) as $r)
                                    <td class="table-{{$data1[$r]->color}}">{{moneyFormat($data1[$r]->f_contracted,2)}}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td>Forecasted Uncontracted</td>
                                @foreach(range(1,12) as $r)
                                    <td class="table-{{$data1[$r]->color}}">{{moneyFormat($data1[$r]->f_uncontracted,2)}}</td>
                                @endforeach
                            </tr>

                            <tr>
                                <td>Opex</td>
                                @foreach(range(1,12) as $r)
                                    <td class="table-{{$data1[$r]->color}}">{{moneyFormat($data1[$r]->opex,2)}}</td>
                                @endforeach
                            </tr>

                            <tr>
                                <td>Forecasted Opex</td>
                                @foreach(range(1,12) as $r)
                                    <td class="table-{{$data1[$r]->color}}">{{moneyFormat($data1[$r]->fopex,2)}}</td>
                                @endforeach
                            </tr>

                            <tr>
                                <td>Forecasted Capex</td>
                                @foreach(range(1,12) as $r)
                                    <td class="table-{{$data1[$r]->color}}">{{moneyFormat($data1[$r]->fcapex,2)}}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td colspan="13">
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td>Actual Revenue</td>
                                @foreach(range(1,12) as $r)
                                    <td class="table-{{$data1[$r]->color}}">{{moneyFormat($data1[$r]->actual,2)}}</td>
                                @endforeach
                            </tr>


                        </tbody>
                    </table>








                    <h6 class="card-title mb-3">{{$y2}}</h6>

                    <table class="table table-sm" >
                        <thead>
                        <tr>
                            <th>Item</th>
                            <th>Jan</th>
                            <th>Feb</th>
                            <th>Mar</th>
                            <th>Apr</th>
                            <th>May</th>
                            <th>Jun</th>
                            <th>Jul</th>
                            <th>Aug</th>
                            <th>Sep</th>
                            <th>Oct</th>
                            <th>Nov</th>
                            <th>Dec</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Contracted Revenue</td>
                            @foreach(range(1,12) as $r)
                                <td class="table-{{$data2[$r]->color}}">{{moneyFormat($data2[$r]->contracted,2)}}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Uncontracted Revenue</td>
                            @foreach(range(1,12) as $r)
                                <td class="table-{{$data2[$r]->color}}">{{moneyFormat($data2[$r]->uncontracted,2)}}</td>
                            @endforeach
                        </tr>

                        <tr>
                            <td>Forecasted Contracted</td>
                            @foreach(range(1,12) as $r)
                                <td class="table-{{$data2[$r]->color}}">{{moneyFormat($data2[$r]->f_contracted,2)}}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Forecasted Uncontracted</td>
                            @foreach(range(1,12) as $r)
                                <td class="table-{{$data2[$r]->color}}">{{moneyFormat($data2[$r]->f_uncontracted,2)}}</td>
                            @endforeach
                        </tr>

                        <tr>
                            <td>Opex</td>
                            @foreach(range(1,12) as $r)
                                <td class="table-{{$data2[$r]->color}}">{{moneyFormat($data2[$r]->opex,2)}}</td>
                            @endforeach
                        </tr>

                        <tr>
                            <td>Forecasted Opex</td>
                            @foreach(range(1,12) as $r)
                                <td class="table-{{$data2[$r]->color}}">{{moneyFormat($data2[$r]->fopex,2)}}</td>
                            @endforeach
                        </tr>

                        <tr>
                            <td>Forecasted Capex</td>
                            @foreach(range(1,12) as $r)
                                <td class="table-{{$data2[$r]->color}}">{{moneyFormat($data2[$r]->fcapex,2)}}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td colspan="13">
                                &nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td>Actual Revenue</td>
                            @foreach(range(1,12) as $r)
                                <td class="table-{{$data2[$r]->color}}">{{moneyFormat($data2[$r]->actual,2)}}</td>
                            @endforeach
                        </tr>


                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

@endsection
