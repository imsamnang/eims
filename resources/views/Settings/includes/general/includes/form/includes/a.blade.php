<div class="card m-0">
    <div class="card-body">
        <div class="form-row">
            <div class="col-md-6 mb-3">
                <label data-toggle="tooltip" for="name" class="form-control-label">
                    {{ __("App Name") }}
                    @if(config("pages.form.validate.rules.name"))
                    <span
                        class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                            class="fas fa-asterisk fa-xs"></i></span>
                    @endif

                </label>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fab fa-app-store"></i></span>
                        </div>
                        <input type="text" class="form-control" name="name" id="name"
                            placeholder=""
                            value="{{config("pages.form.data.name")}}"
                            {{config("pages.form.validate.rules.name") ? "required" : ""}} />

                    </div>
                </div>
            </div>
        </div>
        <div class="form-row">
            @if (config('app.languages'))
            @foreach (config('app.languages') as $lang)
            <div class="col-md-6 mb-3">
                <label class="form-control-label" for="{{$lang["code_name"]}}">
                    {{ __($lang["translate_name"]) }}
                    @if(config("pages.form.validate.rules.".$lang["code_name"]))
                    <span class="badge badge-md badge-circle badge-floating badge-danger"
                        style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span>
                    @endif

                </label>
                <input type="text" class="form-control" name="{{$lang["code_name"]}}"
                    id="{{$lang["code_name"]}}"
                    placeholder=""
                    value="{{config("pages.form.data.".$lang["code_name"])}}"
                    {{config("pages.form.validate.rules.".$lang["code_name"]) ? "required" : ""}} />
            </div>
            @endforeach
            @endif
        </div>

        <div class="form-row">
            <div class="col-md-6 mb-3">
                <label data-toggle="tooltip" for="phone" class="form-control-label">
                    {{ __("Phone") }}
                    @if(config("pages.form.validate.rules.phone"))
                    <span
                        class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                            class="fas fa-asterisk fa-xs"></i></span>
                    @endif

                </label>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        </div>
                        <input type="text" class="form-control" name="phone" id="phone"
                            placeholder=""
                            value="{{config("pages.form.data.phone")}}"
                            {{config("pages.form.validate.rules.phone") ? "required" : ""}} />

                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <label data-toggle="tooltip" for="email" class="form-control-label">

                    {{ __("Email") }}

                    @if(config("pages.form.validate.rules.email")) <span
                        class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                            class="fas fa-asterisk fa-xs"></i></span>
                    @endif

                </label>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        </div>
                        <input type="text" class="form-control" name="email" id="email"
                            placeholder=""
                            value="{{config("pages.form.data.email")}}"
                            {{config("pages.form.validate.rules.email") ? "required" : ""}} />

                    </div>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-6 mb-3">
                <label data-toggle="tooltip" for="address" class="form-control-label">

                    {{ __("Address") }}

                    @if(config("pages.form.validate.rules.address")) <span
                        class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                            class="fas fa-asterisk fa-xs"></i></span>
                    @endif

                </label>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                        </div>
                        <textarea type="text" class="form-control" name="address" id="address"
                            placeholder=""
                            value="{{config("pages.form.data.address")}}"
                            {{config("pages.form.validate.rules.address") ? "required" : ""}}>{{config("pages.form.data.address")}}</textarea>

                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <label data-toggle="tooltip" for="website" class="form-control-label">

                    {{ __("Website") }}

                    @if(config("pages.form.validate.rules.website")) <span
                        class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                            class="fas fa-asterisk fa-xs"></i></span>
                    @endif

                </label>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-globe"></i></span>
                        </div>
                        <input type="text" class="form-control" name="website" id="website"
                            placeholder=""
                            value="{{config("pages.form.data.website")}}"
                            {{config("pages.form.validate.rules.website") ? "required" : ""}} />

                    </div>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col-md-6 mb-3">
                <label data-toggle="tooltip" for="logo" class="form-control-label">

                    {{ __("Logo") }}

                    @if(config("pages.form.validate.rules.logo")) <span
                        class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                            class="fas fa-asterisk fa-xs"></i></span>
                    @endif

                </label>
                <div class="dropzone dropzone-single" data-toggle="dropzone"
                    data-dropzone-url="{{config("pages.form.data.logo")}}">
                    <div class="fallback">
                        <div class="custom-file">
                            <input type="file" placeholder=""
                                class="custom-file-input" id="dropzoneBasicUpload" name="logo"
                                {{config("pages.form.validate.rules.logo") ? "required" : ""}} />
                            <label data-toggle="tooltip" rel="tooltip" data-placement="top" title="123"
                                class="custom-file-label"
                                for="dropzoneBasicUpload">{{ __("Choose image") }}</label>
                        </div>
                    </div>

                    <div class="dz-preview dz-preview-single">
                        <div class="dz-preview-cover">
                            <img class="dz-preview-img" src="{{config("pages.form.data.logo")}}"
                                alt data-dz-thumbnail>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-6">
                <label data-toggle="tooltip" for="favicon" class="form-control-label">

                    {{ __("Favicon") }}

                    @if(config("pages.form.validate.rules.favicon")) <span
                        class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                            class="fas fa-asterisk fa-xs"></i></span>
                    @endif
                </label>

                <div class="dropzone dropzone-single" data-toggle="dropzone"
                    data-dropzone-url="{{config("pages.form.data.favicon")}}">
                    <div class="fallback">
                        <div class="custom-file">
                            <input type="file" placeholder=""
                                class="custom-file-input" id="dropzoneBasicUpload" name="favicon"
                                {{config("pages.form.validate.rules.favicon") ? "required" : ""}} />
                            <label data-toggle="tooltip" rel="tooltip" data-placement="top" title="123"
                                class="custom-file-label"
                                for="dropzoneBasicUpload">{{ __("Choose image") }}</label>
                        </div>
                    </div>

                    <div class="dz-preview dz-preview-single">
                        <div class="dz-preview-cover">
                            <img class="dz-preview-img" src="{{config("pages.form.data.favicon")}}"
                                alt data-dz-thumbnail>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
