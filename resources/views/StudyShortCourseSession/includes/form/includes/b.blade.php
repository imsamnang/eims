<div class="card">
    <div class="card-header p-2 px-3">
        <label class="label-arrow label-primary label-arrow-right">
            (B) {{ Translator:: phrase("location") }}
        </label>
    </div>

    <div class="card-body">
        <div class="form-row" data-collapse="pob" data-control-value-id="pob">
            <div class="col-md-6 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top" title="123" class="form-control-label"
                    for="province">
                    {{ Translator:: phrase("province") }}

                    @if(config("pages.form.validate.rules.province"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif
                </label>

                <select class="form-control" data-toggle="select" id="province" title="Simple select"
                    data-text="{{ Translator::phrase("add_new_option") }}" data-allow-clear="true"
                    data-placeholder="{{ Translator::phrase("choose.province") }}" name="province"
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
                    {{ Translator:: phrase("district") }}
                    @if(config("pages.form.validate.rules.district"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif
                </label>


                <select disabled {{config("pages.form.data.place_of_birth.district.id")? "" :"disabled"}}
                    class="form-control" data-toggle="select" id="district" title="Simple select"
                    data-text="{{ Translator::phrase("add_new_option") }}" data-allow-clear="true"
                    data-placeholder="{{ Translator::phrase("choose.district") }}" name="district"
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
                    {{ Translator:: phrase("commune") }}
                    @if(config("pages.form.validate.rules.commune"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif

                </label>


                <select disabled {{config("pages.form.data.place_of_birth.commune.id")? "" :"disabled"}}
                    class="form-control" data-toggle="select" id="commune" title="Simple select"
                    data-text="{{ Translator::phrase("add_new_option") }}" data-allow-clear="true"
                    data-placeholder="{{ Translator::phrase("choose.commune") }}" name="commune"
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
                    {{ Translator:: phrase("village") }}
                    @if(config("pages.form.validate.rules.village"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif

                </label>


                <select disabled {{config("pages.form.data.place_of_birth.village.id")? "" :"disabled"}}
                    class="form-control" data-toggle="select" id="village" title="Simple select"
                    data-text="{{ Translator::phrase("add_new_option") }}" data-allow-clear="true"
                    data-placeholder="{{ Translator::phrase("choose.village") }}" name="village"
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
