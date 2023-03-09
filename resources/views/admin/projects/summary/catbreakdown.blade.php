<div class="mt-3">
    <h5>Budgeted Time |<small class="text-muted fs-6"> Estimated vs. Actual</small></h5>
    <table class="table table-striped table-sm">
        <tbody>
        @foreach($project->categories as $category)
            <tr>
                <td>{{$category->name}}
                    <br/>
                    <div class="progress animated-progress mb-2">
                        <div class="progress-bar bg-info" role="progressbar" style="width: {{$category->progress}}%"
                             aria-valuenow="{{$category->progress}}" aria-valuemin="0"
                             aria-valuemax="100">
                        </div>
                    </div>

                </td>
                <td>{{$category->totalHoursMax}}</td>
                <td>{{$category->totalWorked}}</td>

            </tr>
        @endforeach
        </tbody>
    </table>
</div>
