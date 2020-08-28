<div class="card">
    <div class="card-header py-2 px-3">
        <label class="label-arrow label-primary label-arrow-right">
            A
        </label>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="col-md-8 mb-3">
                <label class="form-control-label" for="study_short_course_schedule">
                    {{ __("Short course schedule") }}

                    @if(array_key_exists("study_short_course_schedule",config("pages.form.validate.rules")))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                            class="fas fa-asterisk fa-xs"></i></span>
                    @endif

                </label>

                <select class="form-control" data-toggle="select" id="study_short_course_schedule" 


                    
                    data-placeholder="" name="study_short_course_schedule"
                    data-select-value="{{config("pages.form.data.study_short_course_schedule.id")}}">
                    @foreach($study_short_course_schedule["data"] as $o)
                    <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">{{ $o["name"]}}</option>
                    @endforeach
                </select>
            </div>
           
        </div>
       
    </div>
</div>
