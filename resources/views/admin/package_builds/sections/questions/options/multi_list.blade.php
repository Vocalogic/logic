<table class="table table-sm">
    <thead>
    <tr>
        <th>Capture Field</th>
        <th>Description</th>
    </tr>
    </thead>
    <tbody>
    @foreach($question->options as $option)
        <tr>
            <td><a href="/admin/package_builds/{{$build->id}}/sections/{{$section->id}}/questions/{{$question->id}}/options/{{$option->id}}">
                    {{$option->option}}
                </a>
            </td>
            <td>{{$option->description}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

