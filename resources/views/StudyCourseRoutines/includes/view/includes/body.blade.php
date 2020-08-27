<style>
    .card .card-body {
        flex: inherit;
    }

    .table-xs td,
    .table-xs th {
        padding: .3rem .3rem !important;
        font-size: .8rem !important;
        text-align: center;
        border: 2px solid var(--app-color);
    }

    .table-xs td,
    .table-xs th {
        vertical-align: middle;

    }

</style>
<div class="card-body">
    <div class="table-responsive">
        <table class="table table-bordered table-xs" data-toggle="course-routine">
            <thead>
                <th>
                    {{ __('Time') }}
                </th>
                @foreach ($days as $day)
                    <th>
                        {{ $day['name'] }}
                    </th>
                @endforeach
            </thead>
            <tbody>
                @foreach ($routines as $times)
                    <tr>
                        @foreach ($times as $routine)
                            @if (gettype($routine) == 'string')
                                <td style="vertical-align: middle;">{{ $routine }}</td>
                            @elseif($routine['teacher'])
                                <td class="text-center"
                                    data-merge="{{ $routine['teacher']['name'] }}-{{ $routine['study_subjects'] }}-{{ $routine['study_class'] }}">
                                    <div class="col"><img src="{{ $routine['teacher']['photo'] }}" class="avatar"></div>
                                    <div class="col">
                                        <strong>{{ $routine['teacher']['name'] }}</strong>
                                    </div>
                                    <div class="col">{{ $routine['teacher']['email'] }}</div>
                                    <div class="col">{{ $routine['teacher']['phone'] }}</div>
                                    <div class="col">
                                        <div class="border">
                                        <div class="col text-weight-bold text-{{config("app.theme_color.name")}}">
                                                {{ $routine['study_subjects'] }}
                                            </div>
                                            <div class="col">
                                                {{ $routine['study_class'] }}
                                            </div>
                                        </div>
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
</div>
