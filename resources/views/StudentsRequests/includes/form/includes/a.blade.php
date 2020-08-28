<div class="card">
    <div class="card-header p-2 px-3">
        <label class="label-arrow label-primary label-arrow-right">
            (A) {{ __("Institute info") }}
        </label>
    </div>
    <div class="card-body">
        <div class="form-row">
            <input type="hidden" name="id" value="{{config("pages.form.data.".$key.".id")}}">
        </div>
        <div class="form-row">
            @if (Auth::user()->role_id == 1)
            <div class="col-md-12 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.institute")}}" class="form-control-label"
                    for="institute">
                    {{ __("Institute") }}
                    @if(config("pages.form.validate.rules.institute"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif
                </label>

                <select class="form-control" data-toggle="select" id="institute" 
                    data-placeholder=""
                    data-select-value="{{config("pages.form.data.".$key.".institute_id",Auth::user()->institute_id)}}"
                    {{config("pages.form.validate.rules.institute") ? "required" : ""}}>
                    @foreach($institute["data"] as $o)
                    <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">{{ $o["name"]}}</option>
                    @endforeach
                </select>
            </div>
            @else
            <input type="hidden" name="institute" id="institute"
                value="{{config("pages.form.data.".$key.".institute_id",Auth::user()->institute_id)}}">
            @endif

            <div class="col-md-4 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.study_program")}}" class="form-control-label"
                    for="study_program">
                    {{ __("Study Program") }}
                    @if(config("pages.form.validate.rules.study_program"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif
                </label>

                <select class="form-control" data-toggle="select" id="study_program" name="study_program"
                     data-placeholder=""
                    data-select-value="{{config("pages.form.data.".$key.".study_program_id",request("programId"))}}"
                    data-append-to="#study_course-{{$key}}"
                    data-append-url="{{$study_course["action"]["list"]}}?programId=">
                    @foreach($study_program["data"] as $o)
                    <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">
                        {{ $o["name"]}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-8 mb-3">

                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.study_course")}}" class="form-control-label"
                    for="study_course">

                    {{ __("Study Course") }}

                    @if(config("pages.form.validate.rules.study_course"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif
                </label>

                <select {{$study_course['data']? "" : "disabled" }} class="form-control" data-toggle="select"
                    id="study_course-{{$key}}"  data-placeholder="" name="study_course"
                    data-select-value="{{config("pages.form.data.".$key.".study_course_id",request("courseId"))}}">
                    @foreach($study_course["data"] as $o)
                    <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">{{ $o["name"]}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.study_generation")}}" class="form-control-label"
                    for="study_generation">

                    {{ __("Study Generation") }}

                    @if(config("pages.form.validate.rules.study_generation"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif
                </label>

                <select class="form-control" data-toggle="select" id="study_generation" name="study_generation" 
                    data-placeholder=""
                    data-select-value="{{config("pages.form.data.".$key.".study_generation_id",request("generationId"))}}">
                    @foreach($study_generation["data"] as $o)
                    <option value="{{$o["id"]}}">{{ $o["name"]}}</option>
                    @endforeach

                </select>
            </div>
            <div class="col-md-4 mb-3">

                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.study_academic_year")}}" class="form-control-label"
                    for="study_academic_year">

                    {{ __("Study Academic Years") }}

                    @if(config("pages.form.validate.rules.study_academic_year"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif
                </label>
                <select class="form-control" data-toggle="select" id="study_academic_year" name="study_academic_year" 
                    data-placeholder=""
                    data-select-value="{{config("pages.form.data.".$key.".study_academic_year_id",request("yearId"))}}">
                    @foreach($study_academic_year["data"] as $o)
                    <option value="{{$o["id"]}}">{{ $o["name"]}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.study_semester")}}" class="form-control-label"
                    for="study_semester">

                    {{ __("Study Semester") }}

                    @if(config("pages.form.validate.rules.study_semester"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif
                </label>
                <select class="form-control" data-toggle="select" id="study_semester" name="study_semester" 
                    data-placeholder=""
                    data-select-value="{{config("pages.form.data.".$key.".study_semester_id",request("semesterId"))}}">
                    @foreach($study_semester["data"] as $o)
                    <option value="{{$o["id"]}}">{{ $o["name"]}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.study_session")}}" class="form-control-label"
                    for="study_session">

                    {{ __("Study Session") }}

                    @if(config("pages.form.validate.rules.study_session"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif
                </label>
                <select class="form-control" data-toggle="select" id="study_session" name="study_session" 
                    data-placeholder=""
                    data-select-value="{{config("pages.form.data.".$key.".study_session_id",request("sessionId"))}}">
                    @foreach($study_session["data"] as $o)
                    <option value="{{$o["id"]}}">{{ $o["name"]}}</option>
                    @endforeach
                </select>
            </div>

        </div>
    </div>
</div>
