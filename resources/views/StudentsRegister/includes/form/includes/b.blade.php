<div class="card">
    <div class="card-header p-2 px-3">
        <label class="label-arrow label-primary label-arrow-right">
            (B) {{ __("Biography") }}
        </label>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="col-md-6 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.first_name_km")}}" class="form-control-label "
                    for="first_name_km">
                    {{ __("First name Khmer") }}
                    @if(config("pages.form.validate.rules.first_name_km"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif
                </label>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-user-tie"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" name="first_name_km" id="first_name_km" placeholder=""
                            value="{{config("pages.form.data.first_name_km")}}"
                            {{config("pages.form.validate.rules.first_name_km") ? "required" : ""}} />

                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.last_name_km")}}" class="form-control-label "
                    for="last_name_km">
                    {{ __("Last name Khmer") }}

                    @if(config("pages.form.validate.rules.last_name_km"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif

                </label>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fal fa-user"></i></span>
                        </div>
                        <input type="text" class="form-control" name="last_name_km" id="last_name_km" placeholder=""
                            value="{{config("pages.form.data.last_name_km")}}"
                            {{config("pages.form.validate.rules.last_name_km") ? "required" : ""}} />
                    </div>
                </div>
            </div>

        </div>


        <div class="form-row">
            <div class="col-md-6 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.first_name_en")}}" class="form-control-label "
                    for="first_name_en">
                    {{ __("First name Latin") }}
                    @if(config("pages.form.validate.rules.first_name_en"))
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
                        <input type="text" class="form-control" name="first_name_en" id="first_name_en" placeholder=""
                            value="{{config("pages.form.data.first_name_en")}}"
                            {{config("pages.form.validate.rules.first_name_en") ? "required" : ""}} />
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.last_name_en")}}" class="form-control-label "
                    for="last_name_en">
                    {{ __("Last name Latin") }}
                    @if(config("pages.form.validate.rules.last_name_en"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif
                </label>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fal fa-user"></i></span>
                        </div>
                        <input type="text" class="form-control" name="last_name_en" id="last_name_en" placeholder=""
                            value="{{config("pages.form.data.last_name_en")}}"
                            {{config("pages.form.validate.rules.last_name_en") ? "required" : ""}} />
                    </div>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col-md-4 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.nationality")}}" class="form-control-label "
                    for="nationality">

                    {{ __("Nationality") }}

                    @if(config("pages.form.validate.rules.nationality"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif

                </label>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-flag"></i></span>
                        </div>
                        <select class="form-control" data-toggle="select" id="nationality" name="nationality" title="Simple select"
                            data-text="{{ __("Add new option") }}" data-allow-clear="true" data-placeholder=""
                            data-select-value="{{config("pages.form.data.nationality.id")}}">
                            @foreach($nationality["data"] as $o)
                            <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">{{ $o["name"]}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
            <div class="col-md-4 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.mother_tong")}}" class="form-control-label"
                    for="mother_tong">
                    {{ __("Mother tong") }}
                    @if(config("pages.form.validate.rules.mother_tong"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif
                </label>

                <select class="form-control" data-toggle="select" id="mother_tong" title="Simple select"
                    data-text="{{ __("Add new option") }}" data-allow-clear="true" data-placeholder=""
                    name="mother_tong" data-select-value="{{config("pages.form.data.mother_tong.id")}}">
                    @foreach($mother_tong["data"] as $o)
                    <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">{{ $o["name"]}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">

                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.national_id")}}" class="form-control-label "
                    for="national_id">

                    {{ __("National Id") }}

                    @if(config("pages.form.validate.rules.national_id"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif

                </label>

                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                        </div>
                        <input type="number" class="form-control" name="national_id" id="national_id" placeholder=""
                            value="{{config("pages.form.data.national_id")}}"
                            {{ config("pages.form.validate.rules.national_id") ? "required" : ""}} />
                    </div>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col-md-4 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top" for="gender"
                    title="{{config("pages.form.validate.questions.gender")}}" class="form-control-label">

                    {{ __("Gender") }}
                    @if(config("pages.form.validate.rules.gender"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif
                </label>
                <div class="form-group form-control col" id="gender" data-type="gender">
                    @if (config('pages.parameters.param1') == "view")

                    @if (config("pages.form.data.gender.id") == 1)
                    <div class="custom-control custom-radio custom-control-inline col-6">
                        <input {{config("pages.form.data.gender.id") == 1 ? "checked" : ""}} data-toggle="radio"
                            type="radio" id="male" name="gender" value="1" class="custom-control-input"
                            {{config("pages.form.validate.rules.gender") ? "required" : ""}} />
                        <label class="custom-control-label" for="male">{{ __("Male") }}</label>
                    </div>
                    @else

                    <div class="custom-control custom-radio custom-control-inline col-6">
                        <input {{config("pages.form.data.gender.id") == 2 ? "checked" : ""}} data-toggle="radio"
                            type="radio" id="female" name="gender" value="2" class="custom-control-input"
                            {{config("pages.form.validate.rules.gender") ? "required" : ""}} />
                        <label class="custom-control-label" for="female">{{ __("Female") }}</label>
                    </div>
                    @endif

                    @else
                    <div class="custom-control custom-radio custom-control-inline col-6">
                        <input {{config("pages.form.data.gender.id") == 1 ? "checked" : ""}} data-toggle="radio"
                            type="radio" id="male" name="gender" value="1" class="custom-control-input"
                            {{config("pages.form.validate.rules.gender") ? "required" : ""}} />
                        <label class="custom-control-label" for="male">{{ __("Male") }}</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline col-6">
                        <input {{config("pages.form.data.gender.id") == 2 ? "checked" : ""}} data-toggle="radio"
                            type="radio" id="female" name="gender" value="2" class="custom-control-input"
                            {{config("pages.form.validate.rules.gender") ? "required" : ""}} />
                        <label class="custom-control-label" for="female">{{ __("Female") }}</label>
                    </div>
                    @endif
                </div>
            </div>


            <div class="col-md-4 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.date_of_birth")}}" class="form-control-label"
                    for="date_of_birth">

                    {{ __("Date of birth") }}
                    @if(config("pages.form.validate.rules.date_of_birth"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif
                </label>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                        </div>
                        <input type="text" class="form-control datepicker" placeholder="" name="date_of_birth"
                            value="{{config("pages.form.data.date_of_birth")}}"
                            {{config("pages.form.validate.rules.date_of_birth") ? "required" : ""}} />
                    </div>
                </div>
            </div>



            <div class="col-md-4 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.marital")}}" class="form-control-label"
                    for="marital">
                    {{ __("Marital") }}
                    @if(config("pages.form.validate.rules.marital"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif
                </label>


                <select class="form-control" data-toggle="select" id="marital" title="Simple select"
                    data-text="{{ __("Add new option") }}" data-allow-clear="true" data-placeholder="" name="marital"
                    data-select-value="{{config("pages.form.data.marital.id")}}">
                    @foreach($marital["data"] as $o)
                    <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">{{ $o["name"]}}</option>
                    @endforeach
                </select>
            </div>


        </div>

    </div>
</div>
