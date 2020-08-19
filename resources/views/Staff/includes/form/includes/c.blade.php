<div class="card">
    <div class="card-header p-2 px-3">
        <label class="label-arrow label-primary label-arrow-right">
            (C) {{ __("Address") }}
        </label>
    </div>

    <div class="card-header p-2 px-4">
        <label class="form-control-label">
            {{ __("Pob") }}
            @if(config("pages.form.validate.rules.pob"))
            <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                <i class="fas fa-asterisk fa-xs"></i>
            </span>
            @endif
        </label>
    </div>
    <div class="card-body">
        <div class="form-row" data-collapse="pob" data-control-value-id="pob">
            <div class="col-md-6 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top" title="123" class="form-control-label"
                    for="pob_province">
                    {{ __("Province") }}

                    @if(config("pages.form.validate.rules.pob_province"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif
                </label>

                <select class="form-control" data-toggle="select" id="pob_province" title="Simple select"
                    data-text="{{ __("Add new option") }}" data-allow-clear="true" data-placeholder=""
                    name="pob_province" data-select-value="{{config("pages.form.data.".$key.".pob_province_id")}}"
                    data-append-to="#pob_district_{{$key}}"
                    data-append-url="{{$districts["action"]["list"]}}?provinceId="
                    {{config("pages.form.validate.rules.pob_province") ? "required" : ""}}>
                    @foreach($provinces["data"] as $o)
                    <option value="{{$o["id"]}}">
                        {{ $o["name"]}}</option>
                    @endforeach
                </select>

            </div>

            <div class="col-md-6 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top" title="123" class="form-control-label"
                    for="pob_district">
                    {{ __("District") }}
                    @if(config("pages.form.validate.rules.pob_district"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif
                </label>


                <select disabled {{config("pages.form.data.".$key.".pob_district_id")? "" :"disabled"}}
                    class="form-control" data-toggle="select" id="pob_district_{{$key}}" title="Simple select"
                    data-text="{{ __("Add new option") }}" data-allow-clear="true" data-placeholder=""
                    name="pob_district" data-select-value="{{config("pages.form.data.".$key.".pob_district_id")}}"
                    data-append-to="#pob_commune_{{$key}}" data-append-url="{{$communes["action"]["list"]}}?districtId="
                    {{config("pages.form.validate.rules.pob_district") ? "required" : ""}}>
                    @foreach($districts["data"] as $o)
                    <option value="{{$o["id"]}}">
                        {{ $o["name"]}}</option>
                    @endforeach
                </select>

            </div>
            <div class="col-md-6 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top" title="123" class="form-control-label"
                    for="pob_commune">
                    {{ __("Commune") }}
                    @if(config("pages.form.validate.rules.pob_commune"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif

                </label>


                <select disabled {{config("pages.form.data.".$key.".pob_commune_id")? "" :"disabled"}}
                    class="form-control" data-toggle="select" id="pob_commune_{{$key}}" title="Simple select"
                    data-text="{{ __("Add new option") }}" data-allow-clear="true" data-placeholder=""
                    name="pob_commune" data-select-value="{{config("pages.form.data.".$key.".pob_commune_id")}}"
                    data-append-to="#pob_village_{{$key}}" data-append-url="{{$villages["action"]["list"]}}?communeId="
                    {{config("pages.form.validate.rules.pob_commune") ? "required" : ""}}>
                    @foreach($communes["data"] as $o)
                    <option value="{{$o["id"]}}">
                        {{ $o["name"]}}</option>
                    @endforeach
                </select>

            </div>

            <div class="col-md-6 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top" title="123" class="form-control-label"
                    for="pob_village">
                    {{ __("Village") }}
                    @if(config("pages.form.validate.rules.pob_village"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif

                </label>


                <select disabled {{config("pages.form.data.".$key.".pob_village_id")? "" :"disabled"}}
                    class="form-control" data-toggle="select" id="pob_village_{{$key}}" title="Simple select"
                    data-text="{{ __("Add new option") }}" data-allow-clear="true" data-placeholder=""
                    name="pob_village" data-select-value="{{config("pages.form.data.".$key.".pob_village_id")}}"
                    {{config("pages.form.validate.rules.pob_village") ? "required" : ""}}>
                    @foreach($villages["data"] as $o)
                    <option value="{{$o["id"]}}">
                        {{ $o["name"]}}</option>
                    @endforeach
                </select>

            </div>



        </div>
        {{-- <a id="hide-show" class="badge badge-warning mb-3" data-toggle="collapse" href="#other_pob" role="button"
            aria-expanded="false" aria-controls="other_pob">{{ __("Other") }}</a> --}}

        <div class="collapse show" id="other_pob" data-control-value-id="other_pob"
            data-toggle-collapse="{{request()->segment(3) == "view" ? "show" : "pob"}}">
            <div class="form-row">
                <div class="col-md-12">
                    <label data-toggle="tooltip" rel="tooltip" data-placement="top" title="123"
                        class="form-control-label" for="permanent_address">

                        {{ __("Permanent address") }}
                        @if (config("pages.form.validate.rules.permanent_address"))
                        <span class="badge badge-md badge-circle badge-floating badge-danger"
                            style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span> @endif
                    </label>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-home"></i></span>
                            </div>
                            <textarea type="text" class="form-control" id="permanent_address" placeholder=""
                                {{config("pages.form.validate.rules.permanent_address") ? "required" : ""}}
                                name="permanent_address">{{config("pages.form.data.".$key.".permanent_address")}}</textarea>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-header p-2 px-4">
        <label data-toggle="tooltip" rel="tooltip" data-placement="top" title="123" class="form-control-label"
            for="pob_resident">

            {{ __("Current resident") }}
            @if(config("pages.form.validate.rules.pob_resident"))
            <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                <i class="fas fa-asterisk fa-xs"></i>
            </span>
            @endif

            @if (request()->segment(3) != "view")
            <button type="button" class="btn btn-primary btn-sm" data-collapse="current" data-toggle="same-values"
                data-same-value="pob" data-append-value="current">{{ __("Same pob") }}</button>
            @endif
        </label>
    </div>
    <div class="card-body">
        <div class="form-row" data-collapse="current" data-control-value-id="current">
            <div class="col-md-6 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top" title="123" class="form-control-label"
                    for="curr_province">
                    {{ __("Province") }}

                    @if(config("pages.form.validate.rules.curr_province"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif
                </label>

                <select class="form-control" data-toggle="select" id="curr_province" title="Simple select"
                    data-text="{{ __("Add new option") }}" data-allow-clear="true" data-placeholder=""
                    name="curr_province" data-select-value="{{config("pages.form.data.".$key.".curr_province_id")}}"
                    data-append-to="#curr_district_{{$key}}"
                    data-append-url="{{$curr_districts["action"]["list"]}}?provinceId="
                    {{config("pages.form.validate.rules.curr_province") ? "required" : ""}}>
                    @foreach($provinces["data"] as $o)
                    <option value="{{$o["id"]}}">
                        {{ $o["name"]}}</option>
                    @endforeach
                </select>

            </div>

            <div class="col-md-6 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top" title="123" class="form-control-label"
                    for="curr_district">
                    {{ __("District") }}
                    @if(config("pages.form.validate.rules.curr_district"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif

                </label>


                <select disabled {{config("pages.form.data.".$key.".curr_district.id")? "" :"disabled"}}
                    class="form-control" data-toggle="select" id="curr_district_{{$key}}" title="Simple select"
                    data-text="{{ __("Add new option") }}" data-allow-clear="true" data-placeholder=""
                    name="curr_district" data-select-value="{{config("pages.form.data.".$key.".curr_district_id")}}"
                    data-append-to="#curr_commune_{{$key}}"
                    data-append-url="{{$curr_communes["action"]["list"]}}?districtId="
                    {{config("pages.form.validate.rules.curr_district") ? "required" : ""}}>
                    @foreach($curr_districts["data"] as $o)
                    <option value="{{$o["id"]}}">
                        {{ $o["name"]}}</option>
                    @endforeach
                </select>

            </div>
            <div class="col-md-6 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top" title="123" class="form-control-label"
                    for="curr_commune">
                    {{ __("Commune") }}
                    @if(config("pages.form.validate.rules.curr_commune"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif

                </label>


                <select disabled {{config("pages.form.data.".$key.".curr_commune.id")? "" :"disabled"}}
                    class="form-control" data-toggle="select" id="curr_commune_{{$key}}" title="Simple select"
                    data-text="{{ __("Add new option") }}" data-allow-clear="true" data-placeholder=""
                    name="curr_commune" data-select-value="{{config("pages.form.data.".$key.".curr_commune_id")}}"
                    data-append-to="#curr_village_{{$key}}"
                    data-append-url="{{$curr_villages["action"]["list"]}}?communeId="
                    {{config("pages.form.validate.rules.curr_commune") ? "required" : ""}}>
                    @foreach($curr_communes["data"] as $o)
                    <option value="{{$o["id"]}}">
                        {{ $o["name"]}}</option>
                    @endforeach
                </select>

            </div>

            <div class="col-md-6 mb-3">
                <label data-toggle="tooltip" rel="tooltip" data-placement="top" title="123" class="form-control-label"
                    for="curr_village">
                    {{ __("Village") }}
                    @if(config("pages.form.validate.rules.curr_village"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                        <i class="fas fa-asterisk fa-xs"></i>
                    </span>
                    @endif

                </label>


                <select disabled {{config("pages.form.data.".$key.".curr_village_id")? "" :"disabled"}}
                    class="form-control" data-toggle="select" id="curr_village_{{$key}}" title="Simple select"
                    data-text="{{ __("Add new option") }}" data-allow-clear="true" data-placeholder=""
                    name="curr_village" data-select-value="{{config("pages.form.data.".$key.".curr_village_id")}}"
                    {{config("pages.form.validate.rules.curr_village") ? "required" : ""}}>
                    @foreach($curr_villages["data"] as $o)
                    <option value="{{$o["id"]}}">
                        {{ $o["name"]}}</option>
                    @endforeach
                </select>

            </div>


        </div>
        {{-- <a class="badge badge-warning mb-3" data-toggle="collapse" href="#other_current" role="button"
            aria-expanded="false" aria-controls="other_current">{{ __("Other") }}</a> --}}

        <div class="collapse show" id="other_current" data-control-value-id="other_current"
            data-toggle-collapse="{{request()->segment(3) == "view" ? "show" : "current"}}">
            <div class="form-row">
                <div class="col-md-12">
                    <label data-toggle="tooltip" rel="tooltip" data-placement="top" title="123"
                        class="form-control-label" for="temporaray_address">

                        {{ __("Temporaray address") }}
                        @if(config("pages.form.validate.rules.temporaray_address"))
                        <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset">
                            <i class="fas fa-asterisk fa-xs"></i>
                        </span>
                        @endif
                        @if (request()->segment(3) != "view")
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="same-values"
                            data-same-value="other_pob"
                            data-append-value="other_current">{{ __("Same permanent address") }}</button>
                        @endif
                    </label>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                            </div>
                            <textarea type="text" class="form-control" id="temporaray_address" placeholder=""
                                {{config("pages.form.validate.rules.temporaray_address") ? "required" : ""}}
                                name="temporaray_address">{{config("pages.form.data.".$key.".temporaray_address")}}</textarea>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
