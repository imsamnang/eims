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
                            value="{{config("pages.form.data.".$key.".id")}}">{{config("pages.form.data.".$key.".id")}}</span>
                    </div>
                    @endif
                </div>


                <div class="form-row">
                    <div class="col-md-12 mb-3">
                        <label class="form-control-label" for="name">
                            {{ __("Institute") }}

                            @if(array_key_exists("name",config("pages.form.validate.rules"))) <span
                                class="badge badge-md badge-circle badge-floating badge-danger"
                                style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span>
                            @endif

                        </label>

                        <div class="form-group">
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"> <i class="fas fa-school"></i> </span>
                                </div>
                                <input type="text" class="form-control" name="name" id="name" placeholder=""
                                    value="{{config("pages.form.data.".$key.".name")}}"
                                    {{(array_key_exists("name", config("pages.form.validate.rules"))) ? "required" : ""}} />

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

                            @if(array_key_exists($lang["code_name"],config("pages.form.validate.rules")))
                            <span class="badge badge-md badge-circle badge-floating badge-danger"
                                style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span>
                            @endif

                        </label>
                        <input type="text" class="form-control" name="{{$lang["code_name"]}}"
                            id="{{$lang["code_name"]}}" placeholder=""
                            value="{{config("pages.form.data.".$key.".".$lang["code_name"])}}"
                            {{(array_key_exists($lang["code_name"], config("pages.form.validate.rules"))) ? "required" : ""}} />
                    </div>
                    @endforeach
                    @endif
                </div>

                <div class="form-row">
                    <div class="col-md-12 mb-3">
                        <label class="form-control-label" for="short_name">
                            {{ __("Short name") }}

                            @if(array_key_exists("short_name",config("pages.form.validate.rules"))) <span
                                class="badge badge-md badge-circle badge-floating badge-danger"
                                style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span>
                            @endif

                        </label>

                        <div class="form-group">
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"> <i class="fas fa-school"></i> </span>
                                </div>
                                <input type="text" class="form-control" name="short_name" id="short_name" placeholder=""
                                    value="{{config("pages.form.data.".$key.".short_name")}}"
                                    {{(array_key_exists("short_name", config("pages.form.validate.rules"))) ? "required" : ""}} />

                            </div>
                        </div>
                    </div>
                </div>


                <div class="form-row">
                    <div class="col-md-6 mb-3">
                        <label class="form-control-label" for="website">
                            {{ __("Website") }}

                            @if(array_key_exists("website",config("pages.form.validate.rules")))
                            <span class="badge badge-md badge-circle badge-floating badge-danger"
                                style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span>
                            @endif

                        </label>

                        <div class="form-group">
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                </div>
                                <input type="url" class="form-control" id="website" placeholder=""
                                    {{(array_key_exists("website", config("pages.form.validate.rules"))) ? "required" : ""}}
                                    name="website" value="{{config("pages.form.data.".$key.".website")}}"/>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-control-label" for="phone">
                            {{ __("Phone") }}

                            @if(array_key_exists("phone",config("pages.form.validate.rules")))
                            <span class="badge badge-md badge-circle badge-floating badge-danger"
                                style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span>
                            @endif

                        </label>

                        <div class="form-group">
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                </div>
                                <input type="url" class="form-control" id="phone" name="phone" placeholder="" value="{{config("pages.form.data.".$key.".phone")}}"
                                    {{(array_key_exists("phone", config("pages.form.validate.rules"))) ? "required" : ""}}
                                    name="phone" />
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
                                <textarea class="form-control" id="address" placeholder=""
                                    {{(array_key_exists("address", config("pages.form.validate.rules"))) ? "required" : ""}}
                                    name="address">{{config("pages.form.data.".$key.".address")}}</textarea>

                            </div>
                        </div>

                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-control-label" for="location">
                            {{ __("Location") }} (Goolge Map)
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
                                <input class="form-control" id="location" data-target="#iflocation" placeholder="" value="{{config("pages.form.data.".$key.".location")}}"
                                    {{(array_key_exists("location", config("pages.form.validate.rules"))) ? "required" : ""}}
                                    name="location" />

                            </div>
                        </div>

                    </div>

                    @if (Internet::conneted())
                    <div class="col-md-12 mb-3">
                        <iframe id="iflocation"
                            src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d20000!2d103.921494!3d13.3272993!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2suk!4v1486486434098"
                            width="100%" height="250" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
                    </div>
                    @endif
                    <div class="col-md-12 mb-3">
                        <label class="form-control-label" for="description">
                            {{ __("Description") }}

                            @if(array_key_exists("description",config("pages.form.validate.rules")))
                            <span class="badge badge-md badge-circle badge-floating badge-danger"
                                style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span>
                            @endif

                        </label>

                        <div class="form-group">
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-info"></i></span>
                                </div>
                                <textarea class="form-control" id="description" placeholder=""
                                    {{(array_key_exists("description", config("pages.form.validate.rules"))) ? "required" : ""}}
                                    name="description">{{config("pages.form.data.".$key.".description")}}</textarea>

                            </div>
                        </div>

                    </div>

                </div>

            </div>

            <div class="col-md-4">
                <div class="form-row">
                    <div class="col-md-12 mb-3">
                        <label class="form-control-label" for="image">
                            {{ __("Image") }}
                            @if(array_key_exists("image",config("pages.form.validate.rules")))
                            <span class="badge badge-md badge-circle badge-floating badge-danger"
                                style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span>
                            @endif
                        </label>
                        <div class="dropzone dropzone-single" data-toggle="dropzone"
                            data-dropzone-url="{{config("pages.form.data.".$key.".logo")}}?type=original">
                            <div class="fallback">
                                <div class="custom-file">
                                    <input type="file" placeholder="" class="custom-file-input" id="dropzoneBasicUpload"
                                        name="logo"
                                        {{(array_key_exists("image", config("pages.form.validate.rules"))) ? "required" : ""}} />
                                    <label data-toggle="tooltip" rel="tooltip" data-placement="top" title="123"
                                        class="custom-file-label"
                                        for="dropzoneBasicUpload">{{ __("Choose image") }}</label>
                                </div>
                            </div>

                            <div class="dz-preview dz-preview-single">
                                <div class="dz-preview-cover">
                                    <img class="dz-preview-img"
                                        data-src="{{config("pages.form.data.".$key.".logo")}}?type=original" alt
                                        data-dz-thumbnail>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                @if (!config("pages.form.data.".$key.""))
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
