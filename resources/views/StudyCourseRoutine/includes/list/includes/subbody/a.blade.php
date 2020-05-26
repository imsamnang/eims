<div class="table-responsive">
    <table class="table table-bordered" data-toggle="course-routine">
        <thead>
            <th width=1 class="font-weight-bold">
                {{Translator::phrase("time")}}
            </th>
            <th width=170 class="font-weight-bold">
                {{Translator::day("monday")}}</th>
            <th width=170 class="font-weight-bold">
                {{Translator::day("tuesday")}}</th>
            <th width=170 class="font-weight-bold">
                {{Translator::day("wednesday")}}</th>
            <th width=170 class="font-weight-bold">
                {{Translator::day("thursday")}}</th>
            <th width=170 class="font-weight-bold">
                {{Translator::day("friday")}}</th>
            <th width=170 class="font-weight-bold">
                {{Translator::day("saturday")}}</th>
            <th width=170 class="font-weight-bold">
                {{Translator::day("sunday")}}</th>

        </thead>
        <tbody>
            @foreach ($row["children"] as $routine)
            <tr>
                <td>
                    <div class="d-flex">
                        <span>{{$routine["times"]["start_time"]}}</span>
                        <span>{{$routine["times"]["end_time"]}}</span>
                    </div>
                </td>
                @foreach ($routine["days"] as $d)
                @if ($d["teacher"])
                <td class="text-center" data-merge="{{$d["teacher"]["id"]}}-{{$d["study_subject"]["id"]}}-{{$d["study_class"]["id"]}}">
                    <span>
                        {{$d["teacher"]["name"]}}
                    <br>
                        {{$d["teacher"]["email"]}}
                    <br>
                        {{$d["teacher"]["phone"]}}
                    </span>
                    <br>
                    <div  class="border">
                        <span>
                            {{$d["study_subject"]["name"]}}
                            <br>
                            {{$d["study_class"]["name"]}}
                        </span>
                    </div>

                </td>
                @else
                <td class="merge"></td>
                @endif

                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
