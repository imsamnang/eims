
<div class="card m-0">
    <div class="card-header py-2 px-3">
        <label class="label-arrow label-primary label-arrow-right">
            A
        </label>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="col-md-12 mb-3">
                <label class="form-control-label" for="study_course_session">
                    {{ __('Study course session') }}

                    @if (array_key_exists('study_course_session', config('pages.form.validate.rules')))
                        <span class="badge badge-md badge-circle badge-floating badge-danger"
                            style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span>
                    @endif

                </label>

                <select class="form-control" data-toggle="select" id="study_course_session" name="study_course_session" title="Simple select"
                    data-text="{{ __('Add new option') }}" data-placeholder=""
                    data-select-value="{{ config('pages.form.data.'.$key.'.study_course_session_id') }}">
                    @foreach ($study_course_session['data'] as $o)
                        <option data-src="{{ $o['image'] }}" value="{{ $o['id'] }}">{{ $o['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <h2>
            <span class="text-muted">
                {{__('ប្រើ Ctrl ដើម្បីជ្រើសរើសក្រឡា(Cell)')}}
            </span>
        </h2>
        <div class="table-responsive">

            <table class="table table-xs table-bordered border" data-toggle="course-routine">
                <thead>
                    <th style="width: 150px">
                        {{ __('Time') }}
                    </th>
                    @foreach ($days['data'] as $day)
                    <th data-value="{{$day['id']}}"> {{ $day['name'] }}</th>
                    @endforeach

                </thead>
                <tbody>

                    @if (config('pages.form.data.'.$key))
                        @foreach (config('pages.form.data.'.$key.'.routines') as $times)
                            <tr>
                                @foreach ($times as $d => $routine)
                                    @if(gettype($routine)  == 'string')
                                    <td>
                                        <div class="row">
                                            <div class="col m-1">
                                                <input type="time" step="1" name="start_time[]" id="start_time"
                                                    class="form-control form-control-sm"
                                                    value="{{ explode('-',$routine)[0] }}">
                                                |
                                            </div>
                                            <div class="col m-1">
                                                <input type="time" step="1" name="end_time[]" id="end_time"
                                                    class="form-control form-control-sm"
                                                    value="{{ explode('-',$routine)[1] }}">
                                            </div>
                                        </div>
                                    </td>
                                    @elseif (@$routine['teacher'])

                                        <td class="cell" data-merge="{{ $routine['teacher'] }}-{{ $routine['study_subjects'] }}-{{ $routine['study_class'] }}">
                                            <input type="hidden" name="days[]" value="{{ $routine['day'] }}">
                                            <div class="m-1">
                                                <select class="form-control form-control-sm" data-toggle="select"
                                                    id="teachers" title="Simple select" name="teachers[]"
                                                    data-text="{{ __('Add new option') }}" data-placeholder=""
                                                    data-select-value="{{ $routine['teacher'] }}"
                                                    {{config('pages.form.validate.rules.teachers') ? 'required' : '' }}>
                                                    @foreach ($teachers['data'] as $o)
                                                        <option data-src="{{ $o['photo'] }}" value="{{ $o['id'] }}">
                                                            {{ $o['first_name'] }} {{ $o['last_name'] }}</option>
                                                    @endforeach

                                                </select>
                                            </div>

                                            <div class="m-1">
                                                <select class="form-control form-control-sm" data-toggle="select"
                                                    id="study_subjects" title="Simple select"
                                                    data-text="{{ __('Add new option') }}" data-placeholder=""
                                                    name="study_subjects[]"
                                                    data-select-value="{{ $routine['study_subjects'] }}"
                                                    {{ config('pages.form.validate.rules.study_subjects') ? 'required' : '' }}>
                                                    @foreach ($study_subjects['data'] as $o)
                                                        <option data-src="{{ $o['image'] }}" value="{{ $o['id'] }}">
                                                            {{ $o['name'] }}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                            <div class="m-1">
                                                <select class="form-control form-control-sm" data-toggle="select"
                                                    id="study_class" title="Simple select" data-placeholder=""
                                                    name="study_class[]"
                                                    data-select-value="{{ $routine['study_class'] }}"
                                                    {{ config('pages.form.validate.rules.study_class') ? 'required' : '' }}>
                                                    @foreach ($study_class['data'] as $o)
                                                        <option data-src="{{ $o['image'] }}" value="{{ $o['id'] }}">
                                                            {{ $o['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                    @else
                                        <td class="cell merge"></td>
                                    @endif
                                @endforeach
                            </tr>

                        @endforeach
                    @else
                        @for ($i = 7; $i < 11; $i++)
                            <tr>
                                <td>
                                    <div class="row">
                                        <div class="col m-1">
                                            <input type="time" step="1" name="start_time[]" id="start_time"
                                                class="form-control form-control-sm" value="{{ $i < 10 ? '0' : ''}}{{ $i }}:30:00">

                                            |
                                        </div>

                                        <div class="col m-1">
                                            <input type="time" step="1" name="end_time[]" id="end_time"
                                                class="form-control form-control-sm" value="{{ $i < 9 ? '0' : ''}}{{$i + 1 }}:30:00">
                                        </div>
                                    </div>

                                </td>
                                @for ($day = 1; $day <= 7; $day++)
                                    <td class="cell">
                                        <input type="hidden" name="days[]" value="{{ $day }}">

                                        @if ($i == 7 && $day == 1)
                                        <div class="m-1">
                                            <select class="form-control form-control-sm" data-toggle="select"
                                                id="teachers" title="Simple select" name="teachers[]"
                                                data-text="{{ __('Add new option') }}" data-placeholder="{{__('Teacher')}}"
                                                data-select-value=""
                                                {{config('pages.form.validate.rules.teachers') ? 'required' : '' }}>
                                                @foreach ($teachers['data'] as $o)
                                                    <option data-src="{{ $o['photo'] }}" value="{{ $o['id'] }}">
                                                        {{ $o['first_name'] }} {{ $o['last_name'] }}</option>
                                                @endforeach

                                            </select>
                                        </div>

                                        <div class="m-1">
                                            <select class="form-control form-control-sm" data-toggle="select"
                                                id="study_subjects" title="Simple select"
                                                data-text="{{ __('Add new option') }}" data-placeholder="{{__('Subjects')}}"
                                                name="study_subjects[]"
                                                data-select-value=""
                                                {{ config('pages.form.validate.rules.study_subjects') ? 'required' : '' }}>
                                                @foreach ($study_subjects['data'] as $o)
                                                    <option data-src="{{ $o['image'] }}" value="{{ $o['id'] }}">
                                                        {{ $o['name'] }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                        <div class="m-1">
                                            <select class="form-control form-control-sm" data-toggle="select"
                                                id="study_class" title="Simple select" data-placeholder="{{__('Class')}}"
                                                name="study_class[]"
                                                data-select-value=""
                                                {{ config('pages.form.validate.rules.study_class') ? 'required' : '' }}>
                                                @foreach ($study_class['data'] as $o)
                                                    <option data-src="{{ $o['image'] }}" value="{{ $o['id'] }}">
                                                        {{ $o['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @endif

                                    </td>
                                @endfor
                            </tr>
                        @endfor
                    @endif
                </tbody>
            </table>

            <div class="d-none tsc-template">
                <div class="m-1">
                    <select data-placeholder="{{__('Teacher')}}" class="form-control form-control-sm" id="teachers" title="Simple select"
                        data-text="{{ __('Add new option') }}" name="teachers[]"
                        data-select-value="{{ config('pages.form.data.teachers') }}"
                        {{ config('pages.form.validate.rules.teachers') ? 'required' : '' }}>
                        @foreach ($teachers['data'] as $o)
                            <option data-src="{{ $o['photo'] }}" value="{{ $o['id'] }}">
                                {{ $o['first_name'] }} {{ $o['last_name'] }}</option>
                        @endforeach

                    </select>
                </div>

                <div class="m-1">
                    <select class="form-control form-control-sm" id="study_subjects" title="Simple select"
                    data-text="{{ __('Add new option') }}" data-placeholder="{{__('Subjects')}}" name="study_subjects[]"
                        data-select-value="{{ config('pages.form.data.study_subjects') }}"
                        {{config('pages.form.validate.rules.study_subjects') ? 'required' : '' }}>
                        @foreach ($study_subjects['data'] as $o)
                            <option data-src="{{ $o['image'] }}" value="{{ $o['id'] }}">{{ $o['name'] }}</option>
                        @endforeach

                    </select>
                </div>
                <div class="m-1">
                    <select class="form-control form-control-sm" id="study_class" title="Simple select"
                        data-placeholder="{{__('Class')}}" name="study_class[]" data-select-value=""
                        {{config('pages.form.validate.rules.study_class') ? 'required' : '' }}>
                        @foreach ($study_class['data'] as $o)
                            <option data-src="{{ $o['image'] }}" value="{{ $o['id'] }}">{{ $o['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
