@if (Auth::user()->role_id == 1)
<div class="card">
    <div class="card-header p-2 px-3">
        <label class="label-arrow label-primary label-arrow-right">
            (A) {{ __("Institute Info") }}
        </label>
    </div>
    <div class="card-body">
        <div class="form-row">


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
                    
                    data-placeholder=""  name="institute"
                    data-select-value="{{config("pages.form.data.".$key.".staff_institute.institute_id")}}"
                    {{config("pages.form.validate.rules.institute") ? "required" : ""}}>
                    @foreach($institute["data"] as $o)
                    <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">{{ $o["name"]}}</option>
                    @endforeach
                </select>
            </div>

        </div>
    </div>
</div>
@else
<input type="hidden" name="institute" value="{{Auth::user()->institute_id}}">
@endif
