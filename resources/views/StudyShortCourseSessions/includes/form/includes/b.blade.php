<div class="card">
    <div class="card-header p-2 px-3">
        <label class="label-arrow label-primary label-arrow-right">
            (B) {{ __("Location") }}
        </label>
    </div>

    <div class="card-body">
        <div class="form-row" data-collapse="pob" data-control-value-id="pob">
            <div class="col-md-6 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top" title="123" class="form-control-label"
                    for="province">
                    {{ __("Province") }}

                    @if(config("pages.form.validate.rules.province"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif
                </label>

                <select class="form-control" data-toggle="select" id="province" 
                     data-allow-clear="true"
                    data-placeholder="" name="province"
                    data-select-value="{{config("pages.form.data.place_of_birth.province.id")}}"
                    data-append-to="#district"
                    data-append-url="{{str_replace("add","?provinceId=",$districts["pages"]["form"]["action"]["add"])}}"
                    {{config("pages.form.validate.rules.province") ? "required" : ""}}>
                    @foreach($provinces["data"] as $o)
                    <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">
                        {{ $o["name"]}}</option>
                    @endforeach
                </select>

            </div>

            <div class="col-md-6 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top" title="123" class="form-control-label"
                    for="district">
                    {{ __("District") }}
                    @if(config("pages.form.validate.rules.district"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif
                </label>


                <select disabled {{config("pages.form.data.place_of_birth.district.id")? "" :"disabled"}}
                    class="form-control" data-toggle="select" id="district" 
                     data-allow-clear="true"
                    data-placeholder="" name="district"
                    data-select-value="{{config("pages.form.data.place_of_birth.district.id")}}"
                    data-append-to="#commune"
                    data-append-url="{{str_replace("add","?districtId=",$communes["pages"]["form"]["action"]["add"])}}"
                    {{config("pages.form.validate.rules.district") ? "required" : ""}}>
                    @foreach($districts["data"] as $o)
                    <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">
                        {{ $o["name"]}}</option>
                    @endforeach
                </select>

            </div>
            <div class="col-md-6 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top" title="123" class="form-control-label"
                    for="commune">
                    {{ __("Commune") }}
                    @if(config("pages.form.validate.rules.commune"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif

                </label>


                <select disabled {{config("pages.form.data.place_of_birth.commune.id")? "" :"disabled"}}
                    class="form-control" data-toggle="select" id="commune" 
                     data-allow-clear="true"
                    data-placeholder="" name="commune"
                    data-select-value="{{config("pages.form.data.place_of_birth.commune.id")}}"
                    data-append-to="#village"
                    data-append-url="{{str_replace("add","?communeId=",$villages["pages"]["form"]["action"]["add"])}}"
                    {{config("pages.form.validate.rules.commune") ? "required" : ""}}>
                    @foreach($communes["data"] as $o)
                    <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">
                        {{ $o["name"]}}</option>
                    @endforeach
                </select>

            </div>

            <div class="col-md-6 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top" title="123" class="form-control-label"
                    for="village">
                    {{ __("Village") }}
                    @if(config("pages.form.validate.rules.village"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif

                </label>


                <select disabled {{config("pages.form.data.place_of_birth.village.id")? "" :"disabled"}}
                    class="form-control" data-toggle="select" id="village" 
                     data-allow-clear="true"
                    data-placeholder="" name="village"
                    data-select-value="{{config("pages.form.data.place_of_birth.village.id")}}"
                    {{config("pages.form.validate.rules.village") ? "required" : ""}}>
                    @foreach($villages["data"] as $o)
                    <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">
                        {{ $o["name"]}}</option>
                    @endforeach
                </select>

            </div>
        </div>
    </div>
</div>
