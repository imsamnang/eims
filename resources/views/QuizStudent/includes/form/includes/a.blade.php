<div class="card">
    <div class="card-header p-2 px-3">
        <label class="label-arrow label-primary label-arrow-right">
            (A)
        </label>
    </div>

    <div class="card-header p-2 px-4">
        <div class="form-row">
            <div class="col-md-4 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.quiz")}}" class="form-control-label" for="quiz">

                    {{ __("Quiz") }}

                    @if(config("pages.form.validate.rules.quiz"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif
                </label>

                <select class="form-control" data-toggle="select" id="quiz" title="Simple select"


                    data-text="{{ __("Add new option") }}"
                    data-placeholder=""
                    data-select-value="{{config("pages.form.data.quiz.id")}}"
                    {{config("pages.form.validate.rules.quiz") ? "required" : ""}}>
                    @foreach($quiz["data"] as $o)
                    <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">{{ $o["name"]}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group form-control col" id="add_by">
            @if (config('pages.parameters.param1') == "view")

            @else
            <div class="custom-control custom-radio custom-control-inline col-4">
                <input checked data-toggle="radio" type="radio" data-hide-collapse="add_by_cid"
                    data-show-collapse="add_by_scid" id="scid" name="add_by" value="scid" class="custom-control-input">
                <label class="custom-control-label" for="scid">
                    <span class="d-none d-sm-block">{{__("Student study course")}}</span>
                    <span class="d-lg-none">{{__("Students")}}</span>
                </label>
            </div>
            <div class="custom-control custom-radio custom-control-inline col-4">
                <input data-toggle="radio" type="radio" data-hide-collapse="add_by_scid" data-show-collapse="add_by_cid"
                    id="cid" name="add_by" value="cid" class="custom-control-input">
                <label class="custom-control-label" for="cid"><span>{{__("Class")}}</span>
                </label>
            </div>

            @endif

        </div>
    </div>
    <div class="card-body">
        <div class="collapse show" id="add_by_scid">
            <div class="form-row">
                <div class="col-md-12 mb-3">
                    <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                        title="{{config("pages.form.validate.questions.student")}}" class="form-control-label"
                        for="student">

                        {{ __("Students") }}


                        <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                            <i class="fas fa-asterisk fa-xs"></i>
                        </span>
                    </label>

                    <select multiple class="form-control" data-toggle="select" id="student" title="Simple select"


                        data-text="{{ __("Add new option") }}"
                        data-placeholder=""
                        data-select-value="{{config("pages.form.data.student.id")}}"
                        {{config("pages.form.validate.rules.student") ? "required" : ""}}>
                        @foreach($student["data"] as $o)
                        <option data-src="{{$o["photo"]}}" value="{{$o["id"]}}">
                            {{ $o["name"]}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="collapse" id="add_by_cid">
            <div class="form-row">
                <div class="col-md-12 mb-3">
                    <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                        title="{{config("pages.form.validate.questions.study_course_session")}}" class="form-control-label"
                        for="study_course_session">

                        {{ __("Study course session") }}


                        <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                            <i class="fas fa-asterisk fa-xs"></i>
                        </span>
                    </label>
                    <select multiple class="form-control" data-toggle="select" id="study_course_session" title="Simple select"

                        data-text="{{ __("Add new option") }}"
                        data-placeholder=""
                        name="study_course_session[]" data-select-value="{{request('course-sessionId')}}">
                        @foreach($study_course_session["data"] as $o)
                        <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">{{ $o["name"]}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
