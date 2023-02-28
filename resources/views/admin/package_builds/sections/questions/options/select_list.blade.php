<table class="table table-striped">
    <thead>
        <tr>
            <th>Option</th>
        </tr>
    </thead>
    <tbody>
    @foreach($question->options as $option)
        <tr>
            <td><a class='live'
                   data-title="Update Option"
                   href="/admin/package_builds/{{$build->id}}/sections/{{$section->id}}/questions/{{$question->id}}/options/{{$option->id}}">
                    {{$option->option}}
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

