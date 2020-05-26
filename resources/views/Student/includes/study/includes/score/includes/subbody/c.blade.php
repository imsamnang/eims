@foreach ($response["data"] as $row)
    <tr class="">
        <td class="text-center id">{{ $row["node"]["id"]}}</td>
        <td class="text-left px-2 name"> {{ $row["node"]["name"]}}</td>
        <td class="text-center gender">{{mb_substr($row["node"]["node"]["gender"]["name"], 0, 1,'utf-8')}}</td>

        @foreach ($row['scores'] as $key => $scores)
            @include(config("pages.parent").".includes.study.includes.score.includes.subbody.d")
        @endforeach

        <td class="text-center total_marks">
            {{$row['total_marks']}}
        </td>
        <td class="text-center average"> {{$row["average"]}}</td>
        <td class="text-center grade"> {{$row["grade"]}}</td>
    </tr>
    @endforeach
