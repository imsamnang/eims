<div class="card">


    <div class="card-header py-2 px-3">
        <label class="label-arrow label-primary label-arrow-right">
            B
        </label>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="col-md-6 mb-3">
                <label class="form-control-label" for="name">
                    {{ __("Name") }}

                    @if(config("pages.form.validate.rules.name")) <span
                        class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                            class="fas fa-asterisk fa-xs"></i></span>
                    @endif

                </label>
                <input type="text" class="form-control" name="name" id="name" placeholder=""
                    value="{{config("pages.form.data.".$key.".name")}}"
                    {{config("pages.form.validate.rules.name") ? "required" : ""}} />

            </div>
        </div>

        <div class="form-row">
            @if (config('app.languages'))
            @foreach (config('app.languages') as $lang)
            <div class="col-md-6 mb-3">
                <label class="form-control-label" for="{{$lang["code_name"]}}">
                    {{ __($lang["translate_name"]) }}

                    @if(config("pages.form.validate.rules.".$lang["code_name"]))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                            class="fas fa-asterisk fa-xs"></i></span>
                    @endif

                </label>
                <input type="text" class="form-control" name="{{$lang["code_name"]}}" id="{{$lang["code_name"]}}"
                    placeholder="" value="{{config("pages.form.data.".$key.".".$lang["code_name"])}}"
                    {{config("pages.form.validate.rules.".$lang["code_name"]) ? "required" : ""}} />
            </div>
            @endforeach
            @endif
        </div>
    </div>
</div>
