
<div class="card m-0">
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
                    title="{{config("pages.form.validate.questions.institute")}}" class="form-control-label"
                    for="institute">
                    {{ __("Institute") }}

                    @if(config("pages.form.validate.rules.institute"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif
                </label>

                <select class="form-control" data-toggle="select" id="institute" name="institute"  title="Simple select"
                    data-text="{{ __("Add new option") }}"
                    data-placeholder=""
                    data-select-value="{{config("pages.form.data.".$key.".institute_id")}}"
                    {{config("pages.form.validate.rules.institute") ? "required" : ""}}>
                    @foreach($institute["data"] as $o)
                    <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">{{ $o["name"]}}</option>
                    @endforeach
                </select>
            </div>
            @else
            <input type="hidden" name="institute" id="institute" value="{{Auth::user()->institute_id}}">
            @endif
        </div>
        <div class="form-row">
            <div class="col-md-4 mb-3">
                <label class="form-control-label" for="study_program">
                    {{ __("Study Program") }}
                    @if(array_key_exists("study_program",config("pages.form.validate.rules")))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                            class="fas fa-asterisk fa-xs"></i></span>
                    @endif
                </label>



                <select class="form-control" data-toggle="select" id="study_program" name="study_program" title="Simple select"
                    data-text="{{ __("Add new option") }}"
                    data-placeholder=""
                    data-select-value="{{config("pages.form.data.".$key.".study_program_id")}}" data-append-to="#study_course"
                    data-append-url="{{$study_course["action"]["list"]}}?programId=">
                    @foreach($study_program["data"] as $o)
                    <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">
                        {{ $o["name"]}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-8 mb-3">
                <label class="form-control-label" for="study_course">
                    {{ __("Study Course") }}

                    @if(array_key_exists("study_course",config("pages.form.validate.rules")))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                            class="fas fa-asterisk fa-xs"></i></span>
                    @endif
                </label>
                <select {{$study_course? "" : "disabled" }} class="form-control"
                    data-toggle="select" id="study_course"  name="study_course" title="Simple select"
                    data-text="{{ __("Add new option") }}"
                    data-placeholder=""
                    data-select-value="{{config("pages.form.data.".$key.".study_course_id")}}">
                    @foreach($study_course["data"] as $o)
                    <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">{{ $o["name"]}}</option>
                    @endforeach
                </select>
            </div>
        </div>


        <div class="form-row">
            <div class="col-md-4 mb-3">
                <label class="form-control-label" for="study_generation">
                    {{ __("Study Generation") }}

                    @if(array_key_exists("study_generation",config("pages.form.validate.rules")))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                            class="fas fa-asterisk fa-xs"></i></span>
                    @endif

                </label>

                <select class="form-control" data-toggle="select" id="study_generation" name="study_generation" title="Simple select"


                    data-text="{{ __("Add new option") }}"
                    data-placeholder=""
                    data-select-value="{{config("pages.form.data.".$key.".study_generation_id")}}">
                    @foreach($study_generation["data"] as $o)
                    <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">{{ $o["name"]}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-control-label" for="academic_year">
                    {{ __("Study Academic Years") }}

                    @if(array_key_exists("study_academic_year",config("pages.form.validate.rules")))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                            class="fas fa-asterisk fa-xs"></i></span>
                    @endif

                </label>

                <select class="form-control" data-toggle="select" id="study_academic_year" name="study_academic_year" title="Simple select"


                    data-text="{{ __("Add new option") }}"
                    data-placeholder=""
                    data-select-value="{{config("pages.form.data.".$key.".study_academic_year_id")}}">
                    @foreach($study_academic_year["data"] as $o)
                    <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">{{ $o["name"]}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-control-label" for="study_semester">
                    {{ __("Study Semester") }}

                    @if(array_key_exists("study_semester",config("pages.form.validate.rules")))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                            class="fas fa-asterisk fa-xs"></i></span>
                    @endif
                </label>
                <select class="form-control" data-toggle="select" id="study_semester" name="study_semester" title="Simple select"


                    data-text="{{ __("Add new option") }}"
                    data-placeholder=""
                    data-select-value="{{config("pages.form.data.".$key.".study_semester_id")}}">
                    @foreach($study_semester["data"] as $o)
                    <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">{{ $o["name"]}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
