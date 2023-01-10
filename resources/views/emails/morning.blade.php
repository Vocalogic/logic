<style>{!! file_get_contents(public_path() . "/assets/oldbs/dist/css/bootstrap.css") !!}</style>

<style>
    @font-face {
        font-family: 'Nunito';
        font-style: normal;
        font-display: auto;
        src: url({{storage_path()}}/fonts/Nunito-Regular.ttf);
    }


    @font-face {
        font-family: 'Nunito-Bold';
        font-style: normal;
        font-display: auto;
        src: url({{storage_path()}}/fonts/Nunito-Bold.ttf);
    }

    @media dompdf {
        * {
            line-height: 1.2;
        }
    }
    body {
        font-family: 'Nunito', sans-serif;
        font-size: 12px;
    }

    h4 {
        font-family: 'Nunito-Bold', sans-serif;
        font-size: 14px;
        font-weight: 700;
    }



</style>

    <div class="container">
        <div class="row">
            <div class="col-lg-12" style="text-align:center; margin-top:20px;">
                <p style="font-size:14px;">{{setting('brand.name')}} Report for {{now()->format("F m, Y")}}</p>
            </div>
        </div>

        <h4>Current Stats</h4>
        <table class="table table-bordered">
            <tr class="active">
                <td align="center" bgcolor="#a9a9a9" color="black"><b>Invoiced Today</b></td>
                <td align="center" bgcolor="#a9a9a9" color="black"><b>Outstanding Balances</b></td>
                <td align="center" bgcolor="#a9a9a9" color="black"><b>Monthly MRR</b></td>
            </tr>
            <tr>
                <td align="center">${{moneyFormat(morning()['widgets']['invoicedToday']->total)}}</td>
                <td align="center">${{moneyFormat(morning()['widgets']['outstandingBalance']->total)}}</td>
                <td align="center">${{moneyFormat(morning()['widgets']['mrr']->total)}}</td>

            </tr>
        </table>

        <div class="row">
            <div class="col-xs-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Latest Lead Activity
                    </div>
                    <div class="panel-body">

                        <dl>
                            @foreach(\App\Models\Lead::where('active', true)->orderBy('updated_at', 'DESC')->take(10)->get() as $lead)
                            <dt>{{$lead->company}}</dt>
                                <dd>{{$lead->lastComment}}</dd>
                            @endforeach
                        </dl>
                    </div>
                </div>


            </div>
            <div class="col-xs-6">
                <span class="text-success">Test</span>


            </div>

        </div>


        @if(!empty(morning()['staleLeads']))
            <h4>Stale Leads</h4>
            <p>
                The following leads have exceeded the threshold for an update. To fix this, select a lead and enter a
                public
                or private comment.
            </p>
        @endif
    </div>
