<tr>
    <td>{{$obj->id}}</td>
    <td>{{$obj->created_at}}</td>
    <td>{{$obj->account?->name}}</td>
    <td>{{\App\Enums\Core\LogSeverity::from($obj->log_level)->getShort()}}</td>
    <td>{{$obj->log}}</td>
    <td>{!!$obj->detail!!}</td>
</tr>
