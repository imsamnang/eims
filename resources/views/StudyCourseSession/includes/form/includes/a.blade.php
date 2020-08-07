<div class="card">
    <div class="card-header py-2 px-3">
        <label class="label-arrow label-primary label-arrow-right">
            A
        </label>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="col-md-8 mb-3">
                <label class="form-control-label" for="study_course_schedule">
                    {{ __("Study course schedule") }}

                    @if(array_key_exists("study_course_schedule",config("pages.form.validate.rules")))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                            class="fas fa-asterisk fa-xs"></i></span>
                    @endif

                </label>

                <select class="form-control" data-toggle="select" id="study_course_schedule" title="Simple select"


                    data-text="{{ __("Add new option") }}"
                    data-placeholder=""
                    data-select-value="{{config("pages.form.data.study_course_schedule.id")}}">
                    @foreach($study_course_schedule["data"] as $o)
                    <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">{{ $o["name"]}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-control-label" for="study_session">
                    {{ __("Study Session") }}

                    @if(array_key_exists("study_session",config("pages.form.validate.rules")))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                            class="fas fa-asterisk fa-xs"></i></span>
                    @endif

                </label>

                <select class="form-control" data-toggle="select" id="study_session" title="Simple select"


                    data-text="{{ __("Add new option") }}"
                    data-placeholder=""
                    data-select-value="{{config("pages.form.data.study_session.id")}}">
                    @foreach($study_session["data"] as $o)
                    <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">{{ $o["name"]}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-row input-daterange datepicker">
            <div class="col-md-4">
                <label class="form-control-label" for="study_start">
                    {{ __("study_start") }}

                    @if(array_key_exists("study_start",config("pages.form.validate.rules")))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                            class="fas fa-asterisk fa-xs"></i></span>
                    @endif
                </label>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                        </div>
                        <input value="{{config("pages.form.data.study_start")}}" class="form-control" placeholder="">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-control-label" for="study_end">
                    {{ __("study_end") }}

                    @if(array_key_exists("study_end",config("pages.form.validate.rules")))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                            class="fas fa-asterisk fa-xs"></i></span>
                    @endif
                </label>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                        </div>
                        <input value="{{config("pages.form.data.study_end")}}" class="form-control" placeholder="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
