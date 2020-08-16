<div class="card">
    <div class="card-header p-2 px-3">
        <label class="label-arrow label-primary label-arrow-right">
            {{ __("Father info") }}
        </label>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="col-md-6 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.father_fullname")}}" class="form-control-label"
                    for="father_fullname">

                    {{ __("Father fullname") }}
                    @if(config("pages.form.validate.rules.father_fullname"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif
                </label>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                        </div>
                        <input type="text" class="form-control" id="father_fullname" placeholder=""
                            value="{{config("pages.form.data.".$key.".staff_guardian.father_fullname")}}"
                            {{config("pages.form.validate.rules.father_fullname") ? "required" : ""}}
                            name="father_fullname" />

                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.father_occupation")}}" class="form-control-label"
                    for="father_occupation">

                    {{ __("Occupation") }}
                    @if(config("pages.form.validate.rules.father_occupation"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif
                </label>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                        </div>
                        <input type="text" class="form-control" id="father_occupation" placeholder=""
                            value="{{config("pages.form.data.".$key.".staff_guardian.father_occupation")}}"
                            {{config("pages.form.validate.rules.father_occupation") ? "required" : ""}}
                            name="father_occupation" />

                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.father_phone")}}" class="form-control-label"
                    for="father_phone">

                    {{ __("Father phone") }}
                    @if(config("pages.form.validate.rules.father_phone"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif

                </label>

                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        </div>
                        <input type="text" class="form-control" id="father_phone" placeholder=""
                            value="{{config("pages.form.data.".$key.".staff_guardian.father_phone")}}"
                            {{config("pages.form.validate.rules.father_phone") ? "required" : ""}}
                            name="father_phone" />


                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.father_email")}}" class="form-control-label"
                    for="father_phone">

                    {{ __("Father email") }}
                    @if(config("pages.form.validate.rules.father_email"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif

                </label>

                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        </div>
                        <input type="text" class="form-control" id="father_email" placeholder=""
                            value="{{config("pages.form.data.".$key.".staff_guardian.father_email")}}"
                            {{config("pages.form.validate.rules.father_email") ? "required" : ""}}
                            name="father_email" />


                    </div>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-12">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.father_extra_info")}}" class="form-control-label "
                    for="father_extra_info">

                    {{ __("Extra info") }}

                    @if(config("pages.form.validate.rules.father_extra_info")) <span
                        class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif

                </label>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-info"></i></span>
                        </div>
                        <textarea type="text" class="form-control" name="father_extra_info" id="father_extra_info"
                            placeholder=""
                            {{config("pages.form.validate.rules.father_extra_info") ? "required" : ""}}>{{config("pages.form.data.".$key.".staff_guardian.father_extra_info")}}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header p-2 px-3">
        <label class="label-arrow label-primary label-arrow-right">
            {{ __("Mother info") }}
        </label>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="col-md-6 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.mother_fullname")}}" class="form-control-label"
                    for="mother_fullname">

                    {{ __("Mother fullname") }}
                    @if(config("pages.form.validate.rules.mother_fullname"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif
                </label>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                        </div>
                        <input type="text" class="form-control" id="mother_fullname" placeholder=""
                            value="{{config("pages.form.data.".$key.".staff_guardian.mother_fullname")}}"
                            {{config("pages.form.validate.rules.mother_fullname") ? "required" : ""}}
                            name="mother_fullname" />

                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.mother_occupation")}}" class="form-control-label"
                    for="mother_occupation">

                    {{ __("Occupation") }}
                    @if(config("pages.form.validate.rules.mother_occupation"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif
                </label>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                        </div>
                        <input type="text" class="form-control" id="mother_occupation" placeholder=""
                            value="{{config("pages.form.data.".$key.".staff_guardian.mother_occupation")}}"
                            {{config("pages.form.validate.rules.mother_occupation") ? "required" : ""}}
                            name="mother_occupation" />

                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.mother_phone")}}" class="form-control-label"
                    for="mother_phone">

                    {{ __("Mother phone") }}
                    @if(config("pages.form.validate.rules.mother_phone"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif

                </label>

                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        </div>
                        <input type="text" class="form-control" id="mother_phone" placeholder=""
                            value="{{config("pages.form.data.".$key.".staff_guardian.mother_phone")}}"
                            {{config("pages.form.validate.rules.mother_phone") ? "required" : ""}}
                            name="mother_phone" />


                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.mother_email")}}" class="form-control-label"
                    for="mother_phone">

                    {{ __("Mother phone") }}
                    @if(config("pages.form.validate.rules.mother_email"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif

                </label>

                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        </div>
                        <input type="text" class="form-control" id="mother_email" placeholder=""
                            value="{{config("pages.form.data.".$key.".staff_guardian.mother_email")}}"
                            {{config("pages.form.validate.rules.mother_email") ? "required" : ""}}
                            name="mother_email" />


                    </div>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-12">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.mother_extra_info")}}" class="form-control-label "
                    for="mother_extra_info">

                    {{ __("Extra info") }}

                    @if(config("pages.form.validate.rules.mother_extra_info")) <span
                        class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif

                </label>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-info"></i></span>
                        </div>
                        <textarea type="text" class="form-control" name="mother_extra_info" id="mother_extra_info"
                            placeholder=""
                            {{config("pages.form.validate.rules.mother_extra_info") ? "required" : ""}}>{{config("pages.form.data.".$key.".staff_guardian.mother_extra_info")}}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header p-2 px-3">
        <label class="label-arrow label-primary label-arrow-right">
            (D) {{ __("Guardian") }}
        </label>
        @if(config("pages.form.validate.rules.guardian"))
        <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
            <i class="fas fa-asterisk fa-xs"></i>
        </span>
        @endif
    </div>
    <div class="card-header p-2 px-4">
        <div class="form-group form-control col" id="guardian">
            @if (config('pages.parameters.param1') == "view")
            @if(config("pages.form.data.".$key.".staff_guardian.guardian_is") == "father")
            <div class="custom-control custom-radio custom-control-inline col-4">
                <input {{config("pages.form.data.".$key.".staff_guardian.guardian_is") == "father" ? "checked" : ""}}
                    data-toggle="radio" type="radio" data-hide-collapse="xguardian" id="father_is_guardian-{{$key}}"
                    name="guardian" value="father" class="custom-control-input">
                <label class="custom-control-label" for="father_is_guardian-{{$key}}">
                    <span class="d-none d-sm-block">{{__("Father is guardian")}}</span>
                    <span class="d-lg-none">{{__("Father")}}</span>
                </label>
            </div>
            @elseif(config("pages.form.data.".$key.".staff_guardian.guardian_is") == "mother")
            <div class="custom-control custom-radio custom-control-inline col-4">
                <input {{config("pages.form.data.".$key.".staff_guardian.guardian_is") == "mother" ? "checked" : ""}}
                    data-toggle="radio" type="radio" data-hide-collapse="xguardian" id="mother_is_guardian-{{$key}}"
                    name="guardian" value="mother" class="custom-control-input">
                <label class="custom-control-label" for="mother_is_guardian-{{$key}}">
                    <span class="d-none d-sm-block">{{__("Mother is guardian")}}</span>
                    <span class="d-lg-none">{{__("Mother")}}</span>
                </label>
            </div>
            @else
            <div class="custom-control custom-radio custom-control-inline col-4">
                <input
                    {{config("pages.form.data.".$key.".staff_guardian.guardian_is") !== "father" && config("pages.form.data.".$key.".staff_guardian.guardian_is") !== "mother"  ? "checked" : ""}}
                    data-toggle="radio" type="radio" data-show-collapse="xguardian" id="other_guardian-{{$key}}" name="guardian"
                    value="other" class="custom-control-input">
                <label class="custom-control-label" for="other_guardian-{{$key}}"><span>{{__("Other")}}</span>
                </label>
            </div>
            @endif
            @else
            <div class="custom-control custom-radio custom-control-inline col-4">
                <input {{config("pages.form.data.".$key.".staff_guardian.guardian_is") == "father" ? "checked" : ""}}
                    data-toggle="radio" type="radio" data-hide-collapse="xguardian" id="father_is_guardian-{{$key}}"
                    name="guardian" value="father" class="custom-control-input">
                <label class="custom-control-label" for="father_is_guardian-{{$key}}" title="{{__("Father is guardian")}}">
                    <span>{{__("Father")}}</span>

                </label>
            </div>
            <div class="custom-control custom-radio custom-control-inline col-4">
                <input {{config("pages.form.data.".$key.".staff_guardian.guardian_is") == "mother" ? "checked" : ""}}
                    data-toggle="radio" type="radio" data-hide-collapse="xguardian" id="mother_is_guardian-{{$key}}"
                    name="guardian" value="mother" class="custom-control-input">
                <label class="custom-control-label" for="mother_is_guardian-{{$key}}" title="{{__("Mother is guardian")}}">
                    <span>{{__("Mother")}}</span>

                </label>
            </div>
            <div class="custom-control custom-radio custom-control-inline col-4">
                <input
                    {{config("pages.form.data.".$key.".staff_guardian.guardian_is") !== "father" && config("pages.form.data.".$key.".staff_guardian.guardian_is") !== "mother"  ? "checked" : ""}}
                    data-toggle="radio" type="radio" data-show-collapse="xguardian" id="other_guardian-{{$key}}" name="guardian"
                    value="other_guardian" class="custom-control-input">
                <label class="custom-control-label" for="other_guardian-{{$key}}"><span>{{__("Other")}}</span>
                </label>
            </div>

            @endif

        </div>
    </div>
    <div class="card-body">
        <div class="collapse {{config("pages.form.data.".$key.".staff_guardian.guardian_is") !== "father" && config("pages.form.data.".$key.".staff_guardian.guardian_is") !== "mother"  ? "show" : ""}}"
            id="xguardian">
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                        title="{{config("pages.form.validate.questions.guardian_fullname")}}" class="form-control-label"
                        for="guardian_fullname">

                        {{ __("Guardian fullname") }}
                        @if(config("pages.form.validate.rules.guardian_fullname"))
                        <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                            <i class="fas fa-asterisk fa-xs"></i>
                        </span>
                        @endif
                    </label>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user-md"></i></span>
                            </div>
                            <input type="text" class="form-control" id="guardian_fullname" placeholder=""
                                value="{{config("pages.form.data.".$key.".staff_guardian.guardian_fullname")}}"
                                {{config("pages.form.validate.rules.guardian_fullname") ? "required" : ""}}
                                name="guardian_fullname" />

                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                        title="{{config("pages.form.validate.questions.guardian_occupation")}}"
                        class="form-control-label" for="guardian_occupation">

                        {{ __("Occupation") }}
                        @if(config("pages.form.validate.rules.guardian_occupation"))
                        <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                            <i class="fas fa-asterisk fa-xs"></i>
                        </span>
                        @endif
                    </label>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user-md"></i></span>
                            </div>
                            <input type="text" class="form-control" id="guardian_occupation" placeholder=""
                                value="{{config("pages.form.data.".$key.".staff_guardian.guardian_occupation")}}"
                                {{config("pages.form.validate.rules.guardian_occupation") ? "required" : ""}}
                                name="guardian_occupation" />

                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                        title="{{config("pages.form.validate.questions.guardian_phone")}}" class="form-control-label"
                        for="guardian_phone">

                        {{ __("Guardian phone") }}
                        @if(config("pages.form.validate.rules.guardian_phone"))
                        <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                            <i class="fas fa-asterisk fa-xs"></i>
                        </span>
                        @endif

                    </label>

                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            </div>
                            <input type="text" class="form-control" id="guardian_phone" placeholder=""
                                value="{{config("pages.form.data.".$key.".staff_guardian.guardian_phone")}}"
                                {{config("pages.form.validate.rules.guardian_phone") ? "required" : ""}}
                                name="guardian_phone" />


                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                        title="{{config("pages.form.validate.questions.guardian_email")}}" class="form-control-label"
                        for="guardian_email">

                        {{ __("Guardian email") }}
                        @if(config("pages.form.validate.rules.guardian_email"))
                        <span class="badge badge-md badge-circle badge-floating badge-danger"
                            style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span> @endif

                    </label>

                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input type="text" class="form-control" id="guardian_email" placeholder=""
                                value="{{config("pages.form.data.".$key.".staff_guardian.guardian_email")}}"
                                {{config("pages.form.validate.rules.guardian_email") ? "required" : ""}}
                                name="guardian_email" />


                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12">
                    <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                        title="{{config("pages.form.validate.questions.guardian_extra_info")}}"
                        class="form-control-label " for="guardian_extra_info">

                        {{ __("Extra info") }}

                        @if(config("pages.form.validate.rules.guardian_extra_info"))
                        <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                            <i class="fas fa-asterisk fa-xs"></i>
                        </span>
                        @endif

                    </label>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-info"></i></span>
                            </div>
                            <textarea type="text" class="form-control" name="guardian_extra_info"
                                id="guardian_extra_info" placeholder=""
                                {{config("pages.form.validate.rules.guardian_extra_info") ? "required" : ""}}>{{config("pages.form.data.".$key.".staff_guardian.guardian_extra_info")}}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
