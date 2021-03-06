<thead class="thead-gray">
    <tr data-height="150">
        <th>
            @if ($response["success"])
            <h4 class="header lh-170">
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
                <span>
                    {{ __("List Attendance for month",['month'=>DateHelper::getDate(request("year")."-".request("month")."-".date("d"))->shortMonthName]) ." ".request("year") }}
                </span>

            </h4>

            @else
            <h4 class="header">
                {{ __("List Attendance for month",['month'=>DateHelper::getDate(request("year")."-".request("month")."-".date("d"))->shortMonthName]) ." ".request("year") }}
            </h4>
            @endif
        </th>
    </tr>

    <tr>

        <th>​</th>
        @for ($i = 1; $i <= DateHelper::daysOfMonth(request("year"),request("month")); $i++) @php
            $setClass=DateHelper::getDate(request("year")."-".request("month")."-".$i)->isToday() ? "bg-blue text-white"
            : "" ;
            $setTitle = "" ;
            @endphp

            @if(array_key_exists($i ,$holiday))
            @if($holiday[$i]["id"]== null)
            @php
            $setClass = DateHelper::getDate(request("year")."-".request("month")."-".$i)->isToday() ? "bg-blue
            text-white" : "bg-pink text-white" ;
            $setTitle = $holiday[$i]["description"];
            @endphp
            @else
            @php
            $setClass = DateHelper::getDate(request("year")."-".request("month")."-".$i)->isToday() ? "bg-blue
            text-white" : "bg-green text-white" ;
            $setTitle = $holiday[$i]["description"];
            @endphp
            @endif
            @endif

            <th>
                {{ __(mb_substr(DateHelper::dayOfWeek(request("year")."-".request("month")."-".$i)["day"], 0, 3,'utf-8')) }}
            </th>
            @endfor
            <th>​</th>
    </tr>

    <tr>
        @if (config("pages.parameters.param1") != "report")
        <th>
            <div class="custom-control custom-checkbox">
                <input class="custom-control-input" id="table-check-all" data-toggle="table-checked"
                    data-checked-controls="table-checked" data-checked-show-controls='[]' type="checkbox">
                <label class="pl-4 custom-control-label" for="table-check-all"></label>
            </div>
        </th>
        @endif
        <th>{{  __("Id") }}​</th>
        <th>{{  __("Name") }}​</th>
        <th>{{  __("Gender") }}​</th>

        @for ($i = 1; $i <= DateHelper::daysOfMonth(request("year"),request("month")); $i++) @php
            $setClass=DateHelper::getDate(request("year")."-".request("month")."-".$i)->isToday() ?"bg-blue text-white"
            : "" ;
            $setTitle = "" ;
            @endphp

            @if(array_key_exists($i ,$holiday))
            @if($holiday[$i]["id"]== null)
            @php
            $setClass=DateHelper::getDate(request("year")."-".request("month")."-".$i)->isToday() ? "bg-blue text-white"
            : "bg-pink text-white" ;
            $setTitle = $holiday[$i]["description"];
            @endphp
            @else
            @php
            $setClass = DateHelper::getDate(request("year")."-".request("month")."-".$i)->isToday() ? "bg-blue
            text-white" : "bg-green text-white" ;
            $setTitle = $holiday[$i]["description"];
            @endphp
            @endif
            @endif

            <th>
                {{$i}}</th>
            @endfor

            <th>{{ (app()->getLocale() == "km" ? " ច្បាប់" : " P") }}​
            </th>
            <th>{{ (app()->getLocale() == "km" ? " ឥ.ច្បាប់" : " A") }}
            </th>
            <th>{{ __("Total") }}</th>

    </tr>
</thead>
