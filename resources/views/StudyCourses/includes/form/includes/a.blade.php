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
            <div class="col-md-6 mb-3">
                <label class="form-control-label" for="study_program">
                    {{ __("Study Program") }}

                    @if(config("pages.form.validate.rules.study_program"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                            class="fas fa-asterisk fa-xs"></i></span>
                    @endif

                </label>

                <select class="form-control" data-toggle="select" id="study_program" name="study_program" 
                     data-placeholder=""
                    data-select-value="{{config("pages.form.data.".$key.".study_program_id")}}"
                    {{config("pages.form.validate.rules.study_program") ? "required" : ""}}>
                    @foreach($study_program["data"] as $o)
                    <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">{{ $o["name"]}}</option>
                    @endforeach

                </select>
            </div>

            {{-- <div class="col-md-3 mb-3">
                <label class="form-control-label" for="course_type">
                    {{ __("Course Type") }}

            @if(array_key_exists("course_type",config("pages.form.validate.rules")))
            <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                    class="fas fa-asterisk fa-xs"></i></span>
            @endif

            </label>

            <select class="form-control" data-toggle="select" id="course_type" 
                 data-placeholder=""
                data-select-value="{{config("pages.form.data.".$key.".course_type.id")}}"
                {{(array_key_exists("course_type",config("pages.form.validate.rules"))) ? "required" : ""}}>
                @foreach($course_type["data"] as $o)
                <option value="{{$o["id"]}}">{{ $o["name"]}}
                    @endforeach

            </select>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-control-label" for="study_modality">
                {{ __("Study Modality") }}

                @if(array_key_exists("study_modality",config("pages.form.validate.rules")))
                <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                        class="fas fa-asterisk fa-xs"></i></span>
                @endif

            </label>

            <select class="form-control" data-toggle="select" id="study_modality" 
                 data-placeholder=""
                data-select-value="{{config("pages.form.data.".$key.".study_modality.id")}}"
                {{(array_key_exists("study_modality",config("pages.form.validate.rules"))) ? "required" : ""}}>
                @foreach($study_modality["data"] as $o)
                <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">{{ $o["name"]}}
                    @endforeach

            </select>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-control-label" for="study_faculty">
                {{ __("Study Faculty") }}

                @if(array_key_exists("study_faculty",config("pages.form.validate.rules")))
                <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                        class="fas fa-asterisk fa-xs"></i></span>
                @endif

            </label>

            <select class="form-control" data-toggle="select" id="study_faculty" 
                 data-placeholder=""
                data-select-value="{{config("pages.form.data.".$key.".study_faculty.id")}}"
                {{(array_key_exists("study_faculty",config("pages.form.validate.rules"))) ? "required" : ""}}>
                @foreach($study_faculty["data"] as $o)
                <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">{{ $o["name"]}}
                    @endforeach

            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-control-label" for="study_overall_fund">
                {{ __("Study Overall fund") }}

                @if(array_key_exists("study_overall_fund",config("pages.form.validate.rules")))
                <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                        class="fas fa-asterisk fa-xs"></i></span>
                @endif

            </label>

            <select class="form-control" data-toggle="select" id="study_overall_fund" 
                 data-placeholder=""
                data-select-value="{{config("pages.form.data.".$key.".study_overall_fund.id")}}"
                {{(array_key_exists("study_overall_fund",config("pages.form.validate.rules"))) ? "required" : ""}}>
                @foreach($study_overall_fund["data"] as $o)
                <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">{{ $o["name"]}}
                    @endforeach

            </select>
        </div> --}}
    </div>


</div>
</div>
