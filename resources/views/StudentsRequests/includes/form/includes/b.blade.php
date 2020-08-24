<div class="card m-0">
    <div class="card-header py-2 px-3">
        <label class="label-arrow label-primary label-arrow-right">
            B
        </label>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="col-md-12 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                    title="{{config("pages.form.validate.questions.students")}}" class="form-control-label"
                    for="students">
                    {{ __("Students") }}
                    @if(config("pages.form.validate.rules.students[]"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif
                </label>

                <select {{config("pages.form.data.".$key.".student_id")? '' : 'multiple'}} class="form-control" data-toggle="select" id="students" title="Simple select"
                    data-placeholder="" name="students[]"
                    data-select-value="{{config("pages.form.data.".$key.".student_id",request("studentsId"))}}">
                    @foreach($students["data"] as $o)
                    <option data-src="{{$o["photo"]}}" value="{{$o["id"]}}">
                        {{ $o["name"]}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
