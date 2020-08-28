<div class="card">
    <div class="card-header py-2 px-3">
        <label class="label-arrow label-primary label-arrow-right">
            A
        </label>
    </div>
    <div class="card-body">
        <div class="form-row">
            @if (Auth::user()->role_id == 1)
                <div class="col-md-12 mb-3">
                    <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                        title="{{ config('pages.form.validate.questions.institute') }}" class="form-control-label"
                        for="institute">
                        {{ __('Institute') }}

                        @if (config('pages.form.validate.rules.institute'))
                            <span class="badge badge-md badge-circle badge-floating badge-danger"
                                style="background:unset">
                                <i class="fas fa-asterisk fa-xs"></i>
                            </span>
                        @endif
                    </label>
                    <select class="form-control" data-toggle="select" id="institute" data-placeholder=""
                        name="institute" data-select-value="{{ config('pages.form.data.' . $key . '.institute_id') }}"
                        {{ config('pages.form.validate.rules.institute') ? 'required' : '' }}>
                        @foreach ($institute['data'] as $o)
                            <option data-src="{{ $o['image'] }}" value="{{ $o['id'] }}">{{ $o['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            @else
                <input type="hidden" name="institute" id="institute" value="{{ Auth::user()->institute_id }}">
            @endif
        </div>


        <div class="form-row">
            <div class="col-md-6 mb-3">
                <label class="form-control-label" for="study_generation">
                    {{ __('Study Generation') }}

                    @if (config('pages.form.validate.rules.study_generation'))
                        <span class="badge badge-md badge-circle badge-floating badge-danger"
                            style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span>
                    @endif

                </label>

                <select class="form-control" data-toggle="select" id="study_generation" data-placeholder=""
                    name="study_generation"
                    data-select-value="{{ config('pages.form.data.' . $key . '.study_generation_id') }}">
                    @foreach ($study_generation['data'] as $o)
                        <option data-src="{{ $o['image'] }}" value="{{ $o['id'] }}">{{ $o['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-control-label" for="study_session">
                    {{ __('Study Session') }}
                    @if (config('pages.form.validate.rules.study_session'))
                        <span class="badge badge-md badge-circle badge-floating badge-danger"
                            style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span>
                    @endif
                </label>
                <select class="form-control" data-toggle="select" id="study_session" data-placeholder=""
                    name="study_session"
                    data-select-value="{{ config('pages.form.data.' . $key . '.study_session_id') }}">
                    @foreach ($study_session['data'] as $o)
                        <option data-src="{{ $o['image'] }}" value="{{ $o['id'] }}">{{ $o['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>        
        <div class="form-row">
            <div class="col-md-6 mb-3">
                <label class="form-control-label" for="study_subject">
                    {{ __('Study subjects') }}
                    @if (config('pages.form.validate.rules.study_subject'))
                        <span class="badge badge-md badge-circle badge-floating badge-danger"
                            style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span>
                    @endif
                </label>
                <select class="form-control" data-toggle="select" id="study_subject" data-placeholder=""
                    name="study_subject"
                    data-select-value="{{ config('pages.form.data.' . $key . '.study_subject_id') }}">
                    @foreach ($study_subjects['data'] as $o)
                        <option data-src="{{ $o['image'] }}" value="{{ $o['id'] }}">{{ $o['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <div class="form-row input-daterange datepicker">
                    <div class="col-md-6">
                        <label class="form-control-label" for="study_start">
                            {{ __('Study start') }}
        
                            @if (config('pages.form.validate.rules.study_start'))
                                <span class="badge badge-md badge-circle badge-floating badge-danger"
                                    style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span>
                            @endif
                        </label>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                </div>
                                <input value="{{ config('pages.form.data.' . $key . '.study_start') }}" class="form-control"
                                    placeholder="" name="study_start">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-control-label" for="study_end">
                            {{ __('Study end') }}
        
                            @if (config('pages.form.validate.rules.study_end'))
                                <span class="badge badge-md badge-circle badge-floating badge-danger"
                                    style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span>
                            @endif
                        </label>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                </div>
                                <input value="{{ config('pages.form.data.' . $key . '.study_end') }}" class="form-control"
                                    placeholder="" name="study_end">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
