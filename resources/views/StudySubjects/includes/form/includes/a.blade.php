<div class="card">
    <div class="card-header py-2 px-3">
        <label class="label-arrow label-primary label-arrow-right">
            A
        </label>
    </div>
    <div class="card-body">


        <div class="form-row">
            <div class="col-md-6 mb-3">
                <label class="form-control-label" for="name">
                    {{ __('Name') }}

                    @if(config("pages.form.validate.rules.name")) <span
                        class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                            class="fas fa-asterisk fa-xs"></i></span>
                    @endif

                </label>
                <input type="text" class="form-control" name="name" id="name" placeholder=""
                    value="{{config("pages.form.data.".$key.".name")}}"
                    {{config("pages.form.validate.rules.name") ? "required" : ""}} />

            </div>
            <div class="col-md-6 mb-3">

                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.course_type")}}" class="form-control-label"
                    for="course_type">

                    {{ __("Course Type") }}

                    @if(config("pages.form.validate.rules.course_type"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif
                </label>

                <select class="form-control" data-toggle="select" id="course_type" 
                    data-placeholder=""
                    data-select-value="{{config("pages.form.data.".$key.".course_type_id",request("courseId"))}}">
                    @foreach($course_type["data"] as $o)
                    <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">{{ $o["name"]}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-row">
            @if (config('app.languages'))
            @foreach (config('app.languages') as $lang)
            <div class="col-md-6 mb-3">
                <label class="form-control-label" for="{{$lang["code_name"]}}">
                    {{ __($lang["translate_name"]) }}

                    @if(config("pages.form.validate.rules.".$lang["code_name"]))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                            class="fas fa-asterisk fa-xs"></i></span>
                    @endif

                </label>
                <input type="text" class="form-control" name="{{$lang["code_name"]}}" id="{{$lang["code_name"]}}"
                    placeholder="" value="{{config("pages.form.data.".$key.".".$lang["code_name"])}}"
                    {{config("pages.form.validate.rules.".$lang["code_name"]) ? "required" : ""}} />
            </div>
            @endforeach
            @endif
        </div>
    </div>
</div>
