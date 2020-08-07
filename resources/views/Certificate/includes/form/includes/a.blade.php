<div class="card m-0">
    <div class="card-header py-2 px-3">
        <label class="label-arrow label-primary label-arrow-right">
            A
        </label>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col">
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

                <div class="form-row">
                    <div class="col-md-6 mb-3">
                        <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                            title="{{(array_key_exists("institute",config("pages.form.validate.questions"))) ?config("pages.form.validate.questions")["institute"] : ""}}"
                            class="form-control-label" for="institute">
                            {{ __("Institute") }}
                            @if(array_key_exists("institute",config("pages.form.validate.rules"))) <span
                                class="badge badge-md badge-circle badge-floating badge-danger"
                                style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span> @endif
                        </label>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-school"></i></span>
                                </div>
                                <select class="form-control" data-toggle="select" id="institute" title="Simple select"
                                    data-minimum-results-for-search="Infinity"
                                    data-placeholder=""
                                    data-select-value="{{config("pages.form.data.institute.id")}}"
                                    {{(array_key_exists("institute", config("pages.form.validate.rules"))) ? "required" : ""}}>

                                    @foreach($institutes["data"] as $o)
                                    <option value="{{$o["id"]}}">{{ $o["name"]}}
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-md-6 mb-3">
                        <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                            title="{{(array_key_exists("type",config("pages.form.validate.questions"))) ?config("pages.form.validate.questions")["type"] : ""}}"
                            class="form-control-label" for="type">
                            {{ __("Type") }}
                            @if(array_key_exists("type",config("pages.form.validate.rules"))) <span
                                class="badge badge-md badge-circle badge-floating badge-danger"
                                style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span>
                            @endif
                        </label>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-columns"></i></span>
                                </div>
                                <select class="form-control" data-toggle="select" id="type" title="Simple select"
                                    data-minimum-results-for-search="Infinity"
                                    data-placeholder=""
                                    data-select-value="{{config("pages.form.data.type")}}">
                                    <option value="student">
                                        {{ __("Student")}}
                                    </option>
                                    <option value="teacher">
                                        {{ __("Teacher")}}
                                    </option>
                                    <option value="staff">
                                        {{ __("Staff")}}
                                    </option>
                                    <option value="other">
                                        {{ __("Other")}}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                            title="{{(array_key_exists("layout",config("pages.form.validate.questions"))) ?config("pages.form.validate.questions")["layout"] : ""}}"
                            class="form-control-label" for="layout">
                            {{ __("Layout") }}
                            @if(array_key_exists("layout",config("pages.form.validate.rules"))) <span
                                class="badge badge-md badge-circle badge-floating badge-danger"
                                style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span>
                            @endif
                        </label>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-columns"></i></span>
                                </div>
                                <select class="form-control" data-change-text="frame_front,frame_background"
                                    data-toggle="select" id="layout" title="Simple select"
                                    data-minimum-results-for-search="Infinity"
                                    data-placeholder=""
                                    data-select-value="{{config("pages.form.data.layout")}}">
                                    <option data-text="(210x297 mm)" value="vertical">
                                        {{ __("Vertical")}}
                                    </option>
                                    <option data-text="(297x210 mm)" value="horizontal">
                                        {{ __("Horizontal")}}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-md-6 mb-3">
                        <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                            title="{{(array_key_exists("name",config("pages.form.validate.questions"))) ?config("pages.form.validate.questions")["name"] : ""}}"
                            class="form-control-label" for="name">
                            {{ __("Name") }}
                            @if(array_key_exists("name",config("pages.form.validate.rules"))) <span
                                class="badge badge-md badge-circle badge-floating badge-danger"
                                style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span>
                            @endif
                        </label>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fad fa-address-card"></i></span>
                                </div>
                                <input class="form-control" id="name" placeholder=""
                                    name="name" value="{{config("pages.form.data.name")}}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-md-6 mb-3">
                        <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                            title="{{(array_key_exists("description",config("pages.form.validate.questions"))) ?config("pages.form.validate.questions")["description"] : ""}}"
                            class="form-control-label " for="description">

                            {{ __("Description") }}

                            @if(array_key_exists("description",config("pages.form.validate.rules")))
                            <span class="badge badge-md badge-circle badge-floating badge-danger"
                                style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span>
                            @endif

                        </label>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-info"></i></span>
                                </div>
                                <textarea type="text" class="form-control" name="description" id="description"
                                    placeholder=""
                                    {{(array_key_exists("description", config("pages.form.validate.rules"))) ? "required" : ""}}>{{config("pages.form.data.description")}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label data-toggle="tooltip" for="front" class="form-control-label">
                            {{ __("Frame Front") }}
                            <span data-change-text-id="frame_front"></span>
                            @if(array_key_exists("front",config("pages.form.validate.rules")))
                            <span class="badge badge-md badge-circle badge-floating badge-danger"
                                style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span>
                            @endif

                        </label>
                        <div class="dropzone dropzone-single" data-toggle="dropzone"
                            data-dropzone-url="{{config("pages.form.data.front")}}">
                            <div class="fallback">
                                <div class="custom-file">
                                    <input type="file" placeholder=""
                                        class="custom-file-input" id="dropzoneBasicUpload" name="front"
                                        {{(array_key_exists("front", config("pages.form.validate.rules"))) ? "required" : ""}} />
                                    <label data-toggle="tooltip" rel="tooltip" data-placement="top" title="123"
                                        class="custom-file-label"
                                        for="dropzoneBasicUpload">{{ __("Choose image") }}</label>
                                </div>
                            </div>

                            <div class="dz-preview dz-preview-single">
                                <div class="dz-preview-cover">
                                    <img class="dz-preview-img" data-src="{{config("pages.form.data.front")}}"
                                        alt data-dz-thumbnail>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
