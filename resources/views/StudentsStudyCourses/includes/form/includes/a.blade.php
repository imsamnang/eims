<div class="card mb-0">
    <div class="card-header p-2 px-3">
        <label class="label-arrow label-primary label-arrow-right">
            (A)
        </label>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="col-md-9 mb-3">
                <label class="form-control-label" for="study_course_session">
                    {{ __('Study course session') }}

                    @if (array_key_exists('study_course_session', config('pages.form.validate.rules')))
                        <span class="badge badge-md badge-circle badge-floating badge-danger"
                            style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span>
                    @endif

                </label>

                <select class="form-control" data-toggle="select" id="study_course_session" title="Simple select"
                    data-text="{{ __('Add new option') }}" data-placeholder="" name="study_course_session"
                    data-select-value="{{ config('pages.form.data.' . $key . '.study_course_session_id') }}">
                    @foreach ($study_course_session['data'] as $o)
                        <option data-src="{{ $o['image'] }}" value="{{ $o['id'] }}">{{ $o['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{ config('pages.form.validate.questions.study_status') }}" class="form-control-label"
                    for="study_status">
                    {{ __('Study status') }}

                    @if (config('pages.form.validate.rules.study_status'))
                        <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                            <i class="fas fa-asterisk fa-xs"></i>
                        </span>
                    @endif
                </label>
                <select class="form-control" data-toggle="select" id="study_status" title="Simple select"
                    data-text="{{ __('Add new option') }}" data-placeholder="" name="study_status"
                    data-select-value="{{ config('pages.form.data.' . $key . '.study_status_id') }}">
                    @foreach ($study_status['data'] as $o)
                        <option data-src="{{ $o['image'] }}" value="{{ $o['id'] }}">{{ $o['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-control-label" for="students">
                    {{ __('Students request study') }}

                    @if (array_key_exists('students[]', config('pages.form.validate.rules')))
                        <span class="badge badge-md badge-circle badge-floating badge-danger"
                            style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span>
                    @endif

                </label>

                <select {{ config('pages.form.role') == 'add' ? 'multiple' : '' }} class="form-control"
                    data-toggle="select" id="students" title="Simple select" data-text="{{ __('Add new option') }}"
                    data-placeholder="" name="students[]"
                    data-select-value="{{ config('pages.form.data.' . $key . '.student_request_id', request('studRequestId')) }}"
                    {{ array_key_exists('students[]', config('pages.form.validate.rules')) ? 'required' : '' }}>
                    @foreach ($students['data'] as $o)
                        <option data-src="{{ $o['photo'] }}" value="{{ $o['id'] }}">
                            {{ $o['name'] }}
                        </option>
                    @endforeach

                </select>
            </div>


            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <div class="custom-checkbox mb-3">
                        <label class="form-control-label"><i class="fas fa-sticky-note "></i>
                            {{ __('Note') }} </label>
                        <br>
                        <label class="form-control-label">
                            <span class="badge badge-md badge-circle badge-floating badge-danger"
                                style="background:unset">
                                <i class="fas fa-asterisk fa-xs"></i></span> <span>
                                {{ __('Field required') }}</span> </label>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
