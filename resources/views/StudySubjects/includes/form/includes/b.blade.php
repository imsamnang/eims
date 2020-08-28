<div class="card">
    <div class="card-header py-2 px-3">
        <label class="label-arrow label-primary label-arrow-right">
            B
        </label>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="col-md-6 mb-3">
                <label class="form-control-label" for="full_mark_theory">
                    {{ __("Full mark theory") }}

                    @if(config("pages.form.validate.rules.full_mark_theory"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                            class="fas fa-asterisk fa-xs"></i></span>
                    @endif

                </label>

                <select class="form-control" data-toggle="select" id="full_mark_theory" 
                    data-minimum-results-for-search="Infinity" data-placeholder=""
                    data-select-value="{{config("pages.form.data.".$key.".full_mark_theory")}}"
                    {{config("pages.form.validate.rules.full_mark_theory") ? "required" : ""}}>
                    @for($i = 10; $i<= 100 ; $i ++) @if (($i % 10)==0 ) <option value="{{$i}}.00">
                        {{$i.".00 ". __("Points") }}</option>
                        @endif

                        @endfor
                </select>


            </div>

            <div class="col-md-6 mb-3">
                <label class="form-control-label" for="pass_mark_theory">
                    {{ __("Pass mark theory") }}

                    @if(config("pages.form.validate.rules.pass_mark_theory"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                            class="fas fa-asterisk fa-xs"></i></span>
                    @endif

                </label>

                <select class="form-control" data-toggle="select" id="pass_mark_theory" 
                    data-minimum-results-for-search="Infinity" data-placeholder=""
                    data-select-value="{{config("pages.form.data.".$key.".pass_mark_theory")}}"
                    {{config("pages.form.validate.rules.pass_mark_theory") ? "required" : ""}}>
                    @for($i = 10; $i<= 100 ; $i ++) @if (($i % 10)==0 ) <option value="{{number_format($i,2)}}">
                        {{number_format($i,2) ." ". __("Points") }}</option>
                        @endif

                        @endfor
                </select>

            </div>

            <div class="col-md-6 mb-3">
                <label class="form-control-label" for="full_mark_practical">
                    {{ __("Full mark practical") }}

                    @if(config("pages.form.validate.rules.full_mark_practical"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                            class="fas fa-asterisk fa-xs"></i></span>
                    @endif

                </label>

                <select class="form-control" data-toggle="select" id="full_mark_practical" 
                    data-minimum-results-for-search="Infinity" data-placeholder=""
                    data-select-value="{{config("pages.form.data.".$key.".full_mark_practical")}}"
                    {{config("pages.form.validate.rules.full_mark_practical") ? "required" : ""}}>
                    @for($i = 10; $i<= 100 ; $i ++) @if (($i % 10)==0 ) <option value="{{number_format($i,2)}}">
                        {{number_format($i,2)." ". __("Points") }}</option>
                        @endif

                        @endfor
                </select>


            </div>

            <div class="col-md-6 mb-3">
                <label class="form-control-label" for="pass_mark_practical">
                    {{ __("Pass mark practical") }}

                    @if(config("pages.form.validate.rules.pass_mark_practical"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                            class="fas fa-asterisk fa-xs"></i></span>
                    @endif

                </label>

                <select class="form-control" data-toggle="select" id="pass_mark_practical" 
                    data-minimum-results-for-search="Infinity" data-placeholder=""
                    data-select-value="{{config("pages.form.data.".$key.".pass_mark_practical")}}"
                    {{config("pages.form.validate.rules.pass_mark_practical") ? "required" : ""}}>

                    @for($i = 10; $i<= 100 ; $i ++) @if (($i % 10)==0 ) <option value="{{number_format($i,2)}}">
                        {{number_format($i,2)." ". __("Points") }}</option>
                        @endif

                        @endfor
                </select>


            </div>
            <div class="col-md-6 mb-3">
                <label class="form-control-label" for="credit_hour">
                    {{ __("Credit hour") }}

                    @if(config("pages.form.validate.rules.credit_hour"))
                    <span class="badge badge-md badge-circle badge-floating badge-danger" style="background:unset"><i
                            class="fas fa-asterisk fa-xs"></i></span>
                    @endif

                </label>

                <select class="form-control" data-toggle="select" id="credit_hour" 
                    data-minimum-results-for-search="Infinity" data-placeholder=""
                    data-select-value="{{config("pages.form.data.".$key.".credit_hour")}}"
                    {{config("pages.form.validate.rules.credit_hour") ? "required" : ""}}>

                    @for($i = 10; $i<= 100 ; $i ++) @if (($i % 10)==0 ) <option value="{{$i}}">
                        {{ $i.' '. __("Hour") }}</option>
                        @endif

                        @endfor
                </select>

            </div>
        </div>
    </div>
</div>
