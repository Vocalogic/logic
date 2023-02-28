<table class="table table-striped">
    <thead>
    <tr>
        <th>Tag</th>
    </tr>
    </thead>
    <tbody>
    @foreach($question->options as $option)
        <tr>
            <td><a class='live' data-title='Update Tag' href="/admin/package_builds/{{$build->id}}/sections/{{$section->id}}/questions/{{$question->id}}/options/{{$option->id}}">
                    {{$option->getTag()?->name}}
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

