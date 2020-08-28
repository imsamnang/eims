<div class="card">
    <div class="card-header p-2 px-3">
        <label class="label-arrow label-primary label-arrow-right">
            (A) {{ __("Institute Info") }}
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
                     data-placeholder="" name="institute"
                    data-select-value="{{config("pages.form.data.".$key.".staff_institute.institute_id")}}"
                    {{config("pages.form.validate.rules.institute") ? "required" : ""}}>
                    @foreach($institute["data"] as $o)
                    <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">{{ $o["name"]}}</option>
                    @endforeach
                </select>
            </div>
            @else
            <input type="hidden" name="institute" value="{{Auth::user()->institute_id}}">
            @endif

            <div class="col-md-6 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.designation")}}" class="form-control-label"
                    for="designation">

                    {{ __("Designation") }}

                    @if(config("pages.form.validate.rules.designation"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif
                </label>

                <select class="form-control" data-toggle="select" id="designation" 
                     data-allow-clear="true"
                    data-select-value="{{config("pages.form.data.".$key.".staff_institute.designation_id")}}"
                    data-placeholder="" name="designation">

                    @foreach($designation["data"] as $o)
                    <option value="{{$o["id"]}}">
                        {{ $o["name"]}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.status")}}" class="form-control-label" for="status">
                    {{ __("Status") }}
                    @if(config("pages.form.validate.rules.status"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif
                </label>

                <select class="form-control" data-toggle="select" id="status" 
                     data-allow-clear="true"
                    data-select-value="{{config("pages.form.data.".$key.".staff_status_id")}}" data-placeholder=""
                    name="status">
                    @foreach($status["data"] as $o)
                    <option value="{{$o["id"]}}">
                        {{ $o["name"]}}</option>
                    @endforeach
                </select>
            </div>




            <div class="col-md-12 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.extra_info")}}" class="form-control-label"
                    for="institute_extra_info">
                    {{ __("Extra info") }}
                    @if(config("pages.form.validate.rules.institute_extra_info"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                            class="fas fa-asterisk fa-xs"></i></span> @endif
                </label>

                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-info"></i></span>
                        </div>
                        <textarea type="text" class="form-control" id="institute_extra_info" placeholder=""
                            {{config("pages.form.validate.rules.institute_extra_info") ? "required" : ""}}
                            name="institute_extra_info">{{config("pages.form.data.".$key.".staff_institute.extra_info")}}</textarea>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
