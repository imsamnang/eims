<div class="card m-0">
    <div class="card-header py-2 px-3">
        <label class="label-arrow label-primary label-arrow-right">
            A
        </label>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-row">

                    @if (Auth::user()->institute_id)
                    <input type="hidden" name="institute" value="{{Auth::user()->institute_id}}">
                    @else
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
                            data-text="{{ __("Add new option") }}" data-placeholder="" name="institute"
                            data-select-value="{{config("pages.form.data.".$key.".institute_id")}}"
                            {{config("pages.form.validate.rules.institute") ? "required" : ""}}>
                            @foreach($institute["data"] as $o)
                            <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">{{ $o["name"]}}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>
                <div class="form-row">
                    <div class="col-md-12 mb-3">
                        <label class="form-control-label" for="staff">
                            {{ __("Staff") }}

                            @if(array_key_exists("staff",config("pages.form.validate.rules")))
                            <span class="badge badge-md badge-circle badge-floating badge-danger"
                                style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span>
                            @endif

                        </label>

                        <select class="form-control" data-toggle="select" id="staff" title="Simple select"
                            data-text="{{ __("Add new option") }}" data-placeholder="" name="staff"
                            data-select-value="{{config("pages.form.data.".$key.".staff_id")}}">
                            @foreach($staff["data"] as $o)
                            <option data-src="{{$o["photo"]}}" value="{{$o["id"]}}">
                                {{ $o["name"]}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-control-label" for="study_subject">
                            {{ __("Study subjects") }}

                            @if(array_key_exists("study_subject",config("pages.form.validate.rules")))
                            <span class="badge badge-md badge-circle badge-floating badge-danger"
                                style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span>
                            @endif

                        </label>

                        <select class="form-control" data-toggle="select" id="study_subject" title="Simple select"
                            data-text="{{ __("Add new option") }}" data-placeholder="" name="study_subject"
                            data-select-value="{{config("pages.form.data.".$key.".study_subject_id")}}">
                            @foreach($study_subject["data"] as $o)
                            <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">{{ $o["name"]}}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="form-row">
                    <div class="col-md-4 mb-3">
                        <label class="form-control-label" for="name">
                            {{ __("Year") }}

                            @if(config("pages.form.validate.rules.year"))
                            <span class="badge badge-md badge-circle badge-floating badge-danger"
                                style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span>
                            @endif

                        </label>
                        <input type="year" class="form-control" name="year" id="year" placeholder=""
                        value="{{config("pages.form.data.".$key.".year")}}"
                            {{config("pages.form.validate.rules.year") ? "required" : ""}} />

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
