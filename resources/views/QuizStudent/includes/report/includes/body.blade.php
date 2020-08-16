<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-xs" style="width: 100%;">

            <thead class="thead-light">
                <tr class="{{$group == 0 ?'':'invisible' }} ">
                    <th width=1>{{__('Id')}}</th>
                    <th>{{__('Name')}}</th>
                    <th width=1>{{__('Gender')}}</th>
                    @if (!request('quizId'))
                    <th>{{__('Quiz')}}</th>
                    @endif
                    @foreach ($questions as $i => $que)
                    <th width=1>{{__('Question')}} {{$i + 1}}</th>
                    @endforeach
                    <th>{{__('Total')}}</th>
                    <th>{{__('Other')}}</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($response as $id => $row)
                <tr>
                    <td>{{$id + 1}}</td>
                    <td>{{$row['name']}}</td>
                    <td>{{$row['gender']}}</td>
                    @if (!request('quizId'))
                    <td>{{$row['quiz']}}</td>
                    @endif
                    @foreach ($row['questions'] as $i => $que)
                    <td>{{$que['points']}}</td>
                    @endforeach
                    <td>{{$row['total_score']}}</td>
                    <td></td>
                </tr>
                @endforeach

            </tbody>
        </table>

        @if ($last)
        <table class="table table-bordered table-hover table-xs">
            @foreach ($genders as $gender)
            <tr>
                <td> {{$gender['title']}}</td>
                <td> {{$gender['text']}}</td>
            </tr>
            @endforeach
        </table>

        <div style="font-size: 13px">
            <div style="float: right">
                @if (app()->getLocale() == 'km')

                <span>ថ្ងែ <strong>{{$date['_day']}}</strong> ទី <strong>{{$date['day']}}</strong> ខែ
                    <strong>{{$date['month']}}</strong> ឆ្នាំ <strong>{{$date['year']}}</strong> </span>
                @else
                <span>{{__('Date')}} {{$date['def']}}</span>
                @endif

            </div>
        </div>
        @endif

    </div>
</div>
