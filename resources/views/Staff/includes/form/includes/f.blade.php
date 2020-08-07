<div class="card">
    <div class="card-header p-2 px-3">
        <label class="label-arrow label-primary label-arrow-right">
            (F) {{ __("Personal contact") }}
        </label>
    </div>
    <div class="card-body">

        <div class="form-row">
            <div class="col-md-6 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.phone")}}" class="form-control-label"
                    for="phone">

                    {{ __("Phone") }}
                    @if(array_key_exists("phone",config("pages.form.validate.rules")))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                            class="fas fa-asterisk fa-xs"></i></span> @endif
                </label>


                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        </div>
                        <input type="phone" class="form-control" placeholder=""
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
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                            class="fas fa-asterisk fa-xs"></i></span> @endif

                </label>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        </div>
                        <input type="email" class="form-control" id="email"
                            placeholder=""
                            {{(array_key_exists("email", config("pages.form.validate.rules"))) ? "required" : ""}}
                            name="email" />
                    </div>
                </div>

            </div>

        </div>

        <div class="form-row">
            <div class="col-md-12 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.extra_info")}}" class="form-control-label"
                    for="student_extra_info">
                    {{ __("Extra info") }}
                    @if(array_key_exists("student_extra_info",config("pages.form.validate.rules")))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                            class="fas fa-asterisk fa-xs"></i></span> @endif
                </label>

                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-info"></i></span>
                        </div>
                        <textarea type="student_extra_info" class="form-control" id="student_extra_info"
                            placeholder=""
                            {{(array_key_exists("student_extra_info", config("pages.form.validate.rules"))) ? "required" : ""}}
                            name="student_extra_info">{{config("pages.form.data.extra_info")}}</textarea>

                    </div>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-6 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.photo")}}" class="form-control-label"
                    for="photo">
                    {{ __("Photo") }} (4 x 6)
                    @if(array_key_exists("photo",config("pages.form.validate.rules")))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                            class="fas fa-asterisk fa-xs"></i></span> @endif
                </label>
                <div class="dropzone dropzone-single" id="photo" data-toggle="dropzone"
                    data-dropzone-url="{{ config("pages.form.data.photo")}}">
                    <div class="fallback">
                        <div class="custom-file">
                            <input type="file" placeholder=""
                                class="custom-file-input" name="photo"
                                {{(array_key_exists("photo", config("pages.form.validate.rules"))) ? "required" : ""}} />
                            <label data-toggle="tooltip" rel="tooltip" data-placement="top" title="123"
                                class="custom-file-label"
                                for="photo">{{ __("Choose Photo") }}</label>
                        </div>
                    </div>

                    <div class="dz-preview dz-preview-single">
                        <div class="dz-preview-cover">
                            <img class="dz-preview-img" data-src="{{ config("pages.form.data.photo")}}" alt
                                data-dz-thumbnail>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
