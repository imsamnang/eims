<thead class="thead-gray">
    <tr data-height="100">
        <th>
            <h4 class="header">
                @if($response["data"])
                <span>
                    {{$response["data"][0]["node"]["study_course_session"]["study_course_schedule"]["study_program"]["name"]}}
                    -
                    {{$response["data"][0]["node"]["study_course_session"]["study_course_schedule"]["study_course"]["name"]}}
                    ({{$response["data"][0]["node"]["study_course_session"]["study_start"]}} &#9866;
                    {{$response["data"][0]["node"]["study_course_session"]["study_end"]}})
                </span>
                <br>
                <span>
                    {{$response["data"][0]["node"]["study_course_session"]["study_course_schedule"]["study_generation"]["name"]}}
                    -
                    {{$response["data"][0]["node"]["study_course_session"]["study_course_schedule"]["study_academic_year"]["name"]}}
                    -
                    {{$response["data"][0]["node"]["study_course_session"]["study_course_schedule"]["study_semester"]["name"]}}
                    -
                    {{$response["data"][0]["node"]["study_course_session"]["study_session"]["name"]}}
                </span>
                <br>
                @endif
                <span>
                    {{ __("List score")}}
                </span>
            </h4>
        </th>
    </tr>

    <tr>
        @if (config("pages.parameters.param1") != "report")
        <th>
            <div class="custom-control custom-checkbox">
                <input class="custom-control-input" id="table-check-all" data-toggle="table-checked"
                    data-checked-controls="table-checked" data-checked-show-controls='["edit","delete"]'
                    type="checkbox">
                <label class="pl-4 custom-control-label" for="table-check-all"></label>
            </div>
        </th>
        @endif

        <th>{{  __("Id") }}​</th>
        <th>{{  __("Name") }}​</th>
        <th>{{  __("Gender") }}​</th>
        @foreach ($response['study_subject'] as $row)
        <th> {{$row['subject']['name'] }}</th>
        @endforeach
        <th>{{  __("Attendance score") }}​</th>
        <th>{{  __("Other score") }}</th>
        <th>{{  __("Total") }}</th>
        <th>{{  __("Average") }}</th>
        <th>{{  __("Grade") }}</th>
    </tr>
</thead>
