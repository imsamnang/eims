<div class="card m-0">
    <div class="card-header p-2 px-3">
        <label class="label-arrow label-primary label-arrow-right">
            (A)
        </label>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="col-md-12 mb-3">
                <label class="form-control-label" for="study_short_course_session">
                    {{ __("Short course session") }}

                    @if(config("pages.form.validate.rules.study_short_course_session"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                            class="fas fa-asterisk fa-xs"></i></span>
                    @endif

                </label>

                <select class="form-control" data-toggle="select" id="study_short_course_session" title="Simple select"
                    data-text="{{ __("Add new option") }}" data-placeholder="" name="study_short_course_session"
                    data-select-value="{{config("pages.form.data.".$key.".stu_sh_c_session_id")}}">
                    @foreach($study_short_course_session["data"] as $o)
                    <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">{{ $o["name"]}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-8 mb-3">
                <label class="form-control-label" for="students">
                    {{ __("Students request study") }}

                    @if(config("pages.form.validate.rules.students[]"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                            class="fas fa-asterisk fa-xs"></i></span>
                    @endif

                </label>

                <select {{config("pages.form.role") == "add" ? "multiple" : ""}} class="form-control"
                    data-toggle="select" id="students" title="Simple select" data-text="{{ __("Add new option") }}"
                    data-placeholder="" name="students[]"
                    data-select-value="{{config("pages.form.data.".$key.".stu_sh_c_request_id",request("studRequestId"))}}"
                    {{config("pages.form.validate.rules.students[]") ? "required" : ""}}>
                    @foreach($students["data"] as $o)
                    <option data-src="{{$o["photo"]}}" value="{{$o["id"]}}">
                        {{ $o["name"]}}</option>
                    @endforeach

                </select>
            </div>


            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <div class="custom-checkbox mb-3">
                        <label class="form-control-label"><i class="fas fa-sticky-note "></i>
                            {{ __("Note") }} </label>
                        <br>
                        <label class="form-control-label">
                            <span class="badge badge-md badge-circle badge-floating badge-danger"
                                style="background:unset">
                                <i class="fas fa-asterisk fa-xs"></i></span> <span>
                                {{ __("Field required") }}</span> </label>


                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
