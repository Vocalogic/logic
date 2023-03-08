<div class="row">
    <div class="col-xs-12">
        <h4 style="text-align: center;">{{$project->name}}</h4>
        <h5 style="text-align: center;">{{$project->description}}</h5>
        <p>
            {!! $project->summary !!}
        </p>
    </div>
</div>

<div class="row mt-3">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>Action Items and Pricing</strong>
            </div>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Item</th>
                    <th>Dates</th>
                    <th>Price</th>
                </tr>
                </thead>
                <tbody>
                @if($project->bill_method == 'Static')
                    <tr>
                        <td><strong>Project Fixed Rate</strong>
                            <br/>
                            <span class="small text-muted">
                                This project has been priced at a fixed rate and is shown here.
                            </span>
                        </td>
                        <td>
                            @if($project->start_date && $project->end_date)
                                {{$project->start_date->format("m/d")}} - {{$project->end_date->format("m/d")}}
                            @else
                                N/A
                            @endif
                        </td>
                        <td><strong>${{moneyFormat($project->static_price)}}</strong></td>
                    </tr>
                @elseif($project->static_price)
                    <tr>
                        <td><strong>Project Base Rate</strong>
                            <br/>
                            <span class="small text-muted">
                                This project has been assigned a base rate for work to be completed.
                            </span>
                        </td>
                        <td>
                            @if($project->start_date && $project->end_date)
                                {{$project->start_date->format("m/d")}} - {{$project->end_date->format("m/d")}}
                            @else
                                N/A
                            @endif
                        </td>
                        <td><strong>${{moneyFormat($project->static_price)}}</strong></td>
                    </tr>
                @endif

                @foreach($project->categories as $category)
                    <tr>
                        <td><strong>{{$category->name}}</strong>
                            <br/>
                            <span class="small text-muted">
                                {{$category->description}}
                            </span>
                        </td>
                        <td>
                            @if($category->start_date && $category->end_date)
                                {{$category->start_date->format("m/d")}} - {{$category->end_date->format("m/d")}}
                            @else
                                N/A
                            @endif
                        </td>
                        <td><strong>${{moneyFormat($category->totalMax)}}</strong></td>
                    </tr>
                @endforeach

                <tr>
                    <td><strong>Project Total</strong>
                        <br/>
                        <small class="text-muted">
                            Total estimated for project.
                        </small>
                    </td>
                    <td>&nbsp;</td>
                    <td><strong>${{moneyFormat($project->totalMax)}}</strong></td>
                </tr>

                </tbody>
            </table>
        </div>

        <p> The pricing shown above is based off of all requirements gathered. A detailed list of items to be
            completed can be found on the following pages.
        </p>
        <p>
            Upon acceptance, this project is scheduled to begin on {{$project->start_date?->format("M d, Y")}} and
            is to be completed on or before {{$project->end_date?->format("M d, Y")}}.
        </p>

        @if($project->approved_on)
            <div class="panel panel-primary">
                <div class="panel-body">
                    {!! $project->msa !!}

                            <img width="300" src="{!! _file($project->signature_id)?->internal !!}">
                    <div class="pull-right">
                            <b>Authorized By:</b> {{$project->signed_name}}
                            <Br/>
                            <b>Authorized From:</b> {{$project->signed_ip}}
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
