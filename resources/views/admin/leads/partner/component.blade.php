<div>
    @if($loadingMessage)
        <div class="alert border-primary">
            <i class="fa fa-spin fa-refresh"></i> {!! $loadingMessage !!}
        </div>
    @endif
    @if($errorMessage)
        <div class="alert border-danger">
            <i class="fa fa-exclamation-triangle"></i> {!! $errorMessage !!}
        </div>
    @endif
    @if(!empty($data))
        <div class="card">
            <div class="card-content">
                <table class="table">
                    <tbody>
                    <tr>
                        <td align="right"><b>Lead Assigned To:</b></td>
                        <td>{{$data['assigned_to']}}</td>
                    </tr>
                    <tr>
                        <td align="right"><b>Last Updated by Partner:</b></td>
                        <td>{{$data['last_updated']}}</td>
                    </tr>
                    <tr>
                        <td align="right"><b>Forecast Date:</b></td>
                        <td>{{$data['forecast_date'] ?: "Not Forecasted"}}</td>
                    </tr>
                    <tr>
                        <td align="right"><b>Quotes:</b></td>
                        <td>
                            @if($data['active_quotes'] && is_array($data['active_quotes']))
                                @foreach($data['active_quotes'] as $quote)
                                    <b>Quote #{{$quote->number}}</b>:
                                    <b>MRR: </b> ${{number_format($quote->mrr,2)}} /
                                    <b>NRC: </b> ${{number_format($quote->nrc,2)}}
                                    <br/>
                                @endforeach
                            @else
                                No Quotes Found
                            @endif
                        </td>
                    </tr>


                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
