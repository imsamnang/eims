<div class="card-body p-0" style="margin-top: 20px">
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-xs" style="width: 100%;">

            <thead class="thead-light">
                <tr class="{{$group == 0 ?'':'invisible' }} ">
                    <th>{{__('Id')}}</th>
                    <th>{{__('Name')}}</th>
                    <th>{{__('Gender')}}</th>
                    <th>{{__('Phone')}}</th>
                    @if (!request('programId'))
                    <th>{{__('Study Program')}}</th>
                    @endif
                    @if (!request('courseId'))
                    <th>{{__('Study Course')}}</th>
                    @endif
                    @if (!request('generationId'))
                    <th>{{__('Study Generation')}}</th>
                    @endif
                    @if (!request('academicId'))
                    <th>{{__('Study Academic year')}}</th>
                    @endif
                    @if (!request('semesterId'))
                    <th>{{__('Study Semester')}}</th>
                    @endif
                    @if (!request('sessionId'))
                    <th>{{__('Study Session')}}</th>
                    @endif

                    <th>{{__('Other')}}</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($response as $id => $row)
                <tr>
                    <td>{{$id + 1}}</td>
                    <td>{{$row['name']}}</td>
                    <td style="text-align: center">{{mb_substr($row['gender'], 0, 1,'utf-8')}}</td>
                    <td>{{$row['phone']?$row['phone'] : __('N/A')}}</td>
                    @if (!request('programId'))
                    <td>{{$row['study_program']}}</td>
                    @endif
                    @if (!request('courseId'))
                    <td>{{$row['study_course']}}</td>
                    @endif
                    @if (!request('generationId'))
                    <td>{{$row['study_generation']}}</td>
                    @endif
                    @if (!request('academicId'))
                    <td>{{$row['study_academic_year']}}</td>
                    @endif
                    @if (!request('semesterId'))
                    <td>{{$row['study_semester']}}</td>
                    @endif
                    @if (!request('sessionId'))
                    <td>{{$row['study_session']}}</td>
                    @endif
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
