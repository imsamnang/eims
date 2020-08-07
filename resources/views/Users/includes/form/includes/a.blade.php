<div class="card m-0">
    <div class="card-header py-2 px-3">
        <label class="label-arrow label-primary label-arrow-right">
            A
        </label>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <div class="form-row">
                    @csrf
                    @if (request()->segment(3) == "view")
                    <div class="col-md-6 mb-3">
                        <label class="form-control-label" for="id">
                            {{ __("Id") }}
                        </label>
                        <span class="form-control" id="id" name="id"
                            value="{{config("pages.form.data.id")}}">{{config("pages.form.data.id")}}</span>
                    </div>
                    @endif
                </div>

                @if (Auth::user()->role_id == 1)
                <div class="form-row">
                    <div class="col-md-12 mb-3">
                        <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                            title="{{config("pages.form.validate.questions.institute")}}" class="form-control-label"
                            for="institute">

                            {{ __("Institute") }}

                            @if(config("pages.form.validate.rules.institute"))
                            <span class="badge badge-md badge-circle badge-floating badge-danger"
                                style="background:unset">
                                <i class="fas fa-asterisk fa-xs"></i>
                            </span>
                            @endif
                        </label>

                        <select class="form-control" data-toggle="select" id="institute" title="Simple select"

                            data-text="{{ __("Add new option") }}"
                            data-placeholder=""
                            data-select-value="{{config("pages.form.data.institute.id")}}"
                            {{config("pages.form.validate.rules.institute") ? "required" : ""}}>
                            @foreach($institute["data"] as $o)
                            <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">{{ $o["name"]}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @else
                <input type="hidden" name="institute" value="{{Auth::user()->institute_id}}">
                @endif


                <div class="form-row">
                    <div class="col-md-6 mb-3">
                        <label class="form-control-label" for="name">
                            {{ __('Name') }}

                            @if(config("pages.form.validate.rules.name")) <span
                                class="badge badge-md badge-circle badge-floating badge-danger"
                                style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span>
                            @endif

                        </label>
                        <input type="text" class="form-control" name="name" id="name" autocomplete="false"
                            placeholder=""
                            value="{{config("pages.form.data.name")}}"
                            {{config("pages.form.validate.rules.name") ? "required" : ""}} />

                    </div>

                    <div class="col-md-6 mb-3">
                        <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                            title="{{config("pages.form.validate.questions.phone")}}" class="form-control-label"
                            for="phone">

                            {{ __("Phone") }}
                            @if(array_key_exists("phone",config("pages.form.validate.rules")))
                            <span class="badge badge-md badge-circle badge-floating badge-danger"
                                style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span> @endif
                        </label>


                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                </div>
                                <input type="phone" class="form-control"
                                    placeholder=""
                                    value="{{config("pages.form.data.phone")}}"
                                    {{(array_key_exists("phone", config("pages.form.validate.rules"))) ? "required" : ""}}
                                    id="phone" name="phone" />

                            </div>
                        </div>

                    </div>

                    <div class="col-md-6 mb-3">
                        <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                            title="{{config("pages.form.validate.questions.email")}}" class="form-control-label"
                            for="email">

                            {{ __("Email") }}
                            @if(array_key_exists("email",config("pages.form.validate.rules")))
                            <span class="badge badge-md badge-circle badge-floating badge-danger"
                                style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span> @endif

                        </label>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="email" class="form-control" id="email" autocomplete="email"
                                    placeholder=""
                                    value="{{config("pages.form.data.email")}}"
                                    {{(array_key_exists("email", config("pages.form.validate.rules"))) ? "required" : ""}}
                                    name="email" />
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6 mb-3">
                        <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                            title="{{config("pages.parameters.param1") == "edit" ? __("Old password will be use if Field password is not input.") :  config("pages.form.validate.questions.password")}}"
                            class="form-control-label" for="password">

                            {{ __("Password") }}
                            @if(array_key_exists("password",config("pages.form.validate.rules")))
                            <span class="badge badge-md badge-circle badge-floating badge-danger"
                                style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span> @endif

                        </label>

                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                </div>
                                <input type="password" class="form-control" id="password" autocomplete="none"
                                    aria-autocomplete="none" placeholder=""
                                    value="{{config("pages.form.data.password")}}"
                                    {{(array_key_exists("password", config("pages.form.validate.rules"))) ? "required" : ""}}
                                    name="password" />

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-control-label" for="address">
                            {{ __("Address") }}

                            @if(array_key_exists("address",config("pages.form.validate.rules")))
                            <span class="badge badge-md badge-circle badge-floating badge-danger"
                                style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span>
                            @endif

                        </label>

                        <div class="form-group">
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-map"></i></span>
                                </div>
                                <textarea class="form-control" id="address"
                                    placeholder=""
                                    {{(array_key_exists("address", config("pages.form.validate.rules"))) ? "required" : ""}}
                                    name="address">{{config("pages.form.data.address")}}</textarea>

                            </div>
                        </div>

                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-control-label" for="location">
                            {{ __("Location") }}
                            @if(array_key_exists("location",config("pages.form.validate.rules")))
                            <span class="badge badge-md badge-circle badge-floating badge-danger"
                                style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span>
                            @endif

                        </label>

                        <div class="form-group">
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text map-marker" data-target="#location"><i
                                            class="fas fa-map-marker-alt"></i></span>
                                </div>
                                <input class="form-control" id="location"
                                    placeholder=""
                                    value="{{config("pages.form.data.location")}}"
                                    {{(array_key_exists("location", config("pages.form.validate.rules"))) ? "required" : ""}}
                                    name="location" />

                            </div>
                        </div>

                    </div>
                    <div class="col-md-6 mb-3">
                        <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                            title="{{config("pages.form.validate.questions.role")}}" class="form-control-label"
                            for="role">

                            {{ __("role") }}

                            @if(config("pages.form.validate.rules.role"))
                            <span class="badge badge-md badge-circle badge-floating badge-danger"
                                style="background:unset">
                                <i class="fas fa-asterisk fa-xs"></i>
                            </span>
                            @endif
                        </label>

                        <select class="form-control check-reference" data-toggle="select" id="role"
                            title="Simple select" data-text="{{ __("Add new option") }}"
                            data-placeholder=""
                            data-select-value="{{config("pages.form.data.role.id",9)}}"
                            {{config("pages.form.validate.rules.role") ? "required" : ""}}>
                            @foreach($role["data"] as $o)
                            <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">{{ $o["name"]}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3 d-none" id="reference-student">
                        <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                            title="{{config("pages.form.validate.questions.student")}}" class="form-control-label"
                            for="student">

                            {{ __("Student") }}


                            <span class="badge badge-md badge-circle badge-floating badge-danger"
                                style="background:unset">
                                <i class="fas fa-asterisk fa-xs"></i>
                            </span>

                        </label>

                        <select disabled class="form-control" data-toggle="select" id="reference" title="Simple select"
                            data-text="{{ __("Add new option") }}"
                            data-placeholder=""
                            data-select-value="{{config("pages.form.data.student.id")}}"
                            {{config("pages.form.validate.rules.student") ? "required" : ""}}>
                            @foreach($student["data"] as $o)
                            <option data-src="{{$o["photo"]}}" value="{{$o["id"]}}">{{ $o["name"]}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3 d-none" id="reference-staff">
                        <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                            title="{{config("pages.form.validate.questions.staff")}}" class="form-control-label"
                            for="staff">

                            {{ __("Staff") }}

                            <span class="badge badge-md badge-circle badge-floating badge-danger"
                                style="background:unset">
                                <i class="fas fa-asterisk fa-xs"></i>
                            </span>

                        </label>

                        <select disabled class="form-control" data-toggle="select" id="reference" title="Simple select"
                            data-text="{{ __("Add new option") }}"
                            data-placeholder=""
                            data-select-value="{{config("pages.form.data.staff.id")}}"
                            {{config("pages.form.validate.rules.staff") ? "required" : ""}}>
                            @foreach($staff["data"] as $o)
                            <option data-src="{{$o["photo"]}}" value="{{$o["id"]}}">{{ $o["name"]}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>

            <div class="col-md-4">
                <div class="form-row">
                    <div class="col-md-12 mb-3">
                        <label class="form-control-label" for="profile">
                            {{ __("Photo") }}
                            @if(config("pages.form.validate.rules.profile"))
                            <span class="badge badge-md badge-circle badge-floating badge-danger"
                                style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span>
                            @endif
                        </label>
                        <div class="dropzone dropzone-single" data-toggle="dropzone"
                            data-dropzone-url="{{config("pages.form.data.profile")}}">
                            <div class="fallback">
                                <div class="custom-file">
                                    <input type="file" placeholder=""
                                        class="custom-file-input" id="dropzoneBasicUpload" name="profile"
                                        {{config("pages.form.validate.rules.profile") ? "required" : ""}} />
                                    <label data-toggle="tooltip" rel="tooltip" data-placement="top" title="123"
                                        class="custom-file-label"
                                        for="dropzoneBasicUpload">{{ __("Choose Photo") }}</label>
                                </div>
                            </div>

                            <div class="dz-preview dz-preview-single">
                                <div class="dz-preview-cover">
                                    <img class="dz-preview-img" data-src="{{config("pages.form.data.profile")}}" alt
                                        data-dz-thumbnail>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                @if (!config("pages.form.data"))
                <div class="form-row">
                    <div class="form-group">
                        <div class="custom-control custom-checkbox mb-3">

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
                @endif
            </div>
        </div>
    </div>
</div>
