<div class="card">
    <div class="card-body">
        <h6 class="card-title">Provisioning Status
            @if($order->provisioning && $order->provisioning->active) <a href="/admin/provisionings/{{$order->provisioning->id}}"><i class="fa fa-search"></i></a>
            @endif
        </h6>

        @if(!$order->provisioning)
            <div class="card mt-3">
                <div class="card-body text-center">
                    <img src="/assets/images/no-data.svg" class="w120" alt="No Data">
                    <div class="mt-4 mb-3">
                        <span class="text-muted">No Provisioning Order Found</span>
                    </div>
                    <a class="btn btn-{{bm()}}primary border lift" href="/admin/provisionings/create?fromOrder={{$order->id}}"><i class="fa fa-plus"></i> Create
                        Provisioning Order</a>
                </div>
            </div>
            @else
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>TNs</th>
                            <th>Exts</th>
                            <th>E911</th>
                            <th>Install</th>
                            <th>QA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{!! count($order->provisioning->tnArray) == $order->provisioning->did_count
                                ? "<i class='text-success fa fa-check'></i>"
                                : "<i class='text-danger fa fa-times'></i>"!!}
                            </td>
                            <td>{!! count($order->provisioning->extensionsArray) == $order->provisioning->extension_count
                                ? "<i class='text-success fa fa-check'></i>"
                                : "<i class='text-danger fa fa-times'></i>"!!}
                            </td>
                            <td>
                                {!! $order->provisioning->e911_address
                                ? "<i class='text-success fa fa-check'></i>"
                                : "<i class='text-danger fa fa-times'></i>"!!}
                            </td>
                            <td>
                                {!! $order->provisioning->install_date
                                ? "<i class='text-success fa fa-check'></i>"
                                : "<i class='text-danger fa fa-times'></i>"!!}
                            </td>
                        </tr>

                    </tbody>
                </table>

        @endif

    </div>

</div>
