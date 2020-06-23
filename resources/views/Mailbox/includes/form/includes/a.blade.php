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
                    @csrf
                    <div class="col-md-12 mb-3">
                        <label class="form-control-label d-none" for="recipient">
                            {{ Translator:: phrase('recipient') }}

                            @if(config("pages.form.validate.rules.recipient")) <span
                                class="badge badge-md badge-circle badge-floating badge-danger"
                                style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span>
                            @endif

                        </label>
                        <select multiple="multiple" class="form-control" data-toggle="select" id="recipient"
                            title="Simple select" data-url="{{$recipient["pages"]["form"]["action"]["add"]}}"
                            data-text="{{ Translator::phrase("add_new_option") }}"
                            data-ajax="{{str_replace("add","list",$recipient["pages"]["form"]["action"]["add"])}}"
                            data-allow-clear="true" data-placeholder="{{Translator::phrase("recipient") }}"
                            name="recipient[]"
                            data-select-value="{{config("pages.form.data.place_of_birth.province.id")}}"
                            {{config("pages.form.validate.rules.recipient") ? "required" : ""}}>
                            @foreach($recipient["data"] as $o)
                            <option data-src="{{$o["profile"]}}" value="{{$o["id"]}}">
                                {{ $o["name"]}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="form-row">
                    <div class="col-md-12 mb-3">
                        <label class="form-control-label d-none" for="subject">
                            {{ Translator:: phrase("subject") }}

                            @if(config("pages.form.validate.rules.subject"))
                            <span class="badge badge-md badge-circle badge-floating badge-danger"
                                style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span>
                            @endif

                        </label>
                        <input type="text" class="form-control" name="subject" id="subject"
                            placeholder="{{ Translator::phrase("subject") }}"
                            value="{{config("pages.form.data.subject")}}"
                            {{config("pages.form.validate.rules.subject") ? "required" : ""}} />

                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-control-label d-none" for="message">
                            {{ Translator:: phrase('message') }}

                            @if(config("pages.form.validate.rules.message")) <span
                                class="badge badge-md badge-circle badge-floating badge-danger"
                                style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span>
                            @endif

                        </label>
                        <div>
                            <div id="toolbar-container">
                                <span class="ql-formats">
                                    <select class="ql-font"></select>
                                    <select class="ql-size"></select>
                                </span>
                                <span class="ql-formats">
                                    <button class="ql-bold"></button>
                                    <button class="ql-italic"></button>
                                    <button class="ql-underline"></button>
                                </span>
                                <span class="ql-formats">
                                    <select class="ql-color"></select>
                                    <select class="ql-background"></select>
                                </span>
                                <span class="ql-formats">
                                    <button class="ql-script" value="sub"></button>
                                    <button class="ql-script" value="super"></button>
                                </span>
                                <span class="ql-formats">
                                    <button class="ql-header" value="1"></button>
                                    <button class="ql-header" value="2"></button>
                                    <button class="ql-code-block"></button>
                                </span>
                                <span class="ql-formats">
                                    <button class="ql-list" value="ordered"></button>
                                    <button class="ql-list" value="bullet"></button>
                                    <button class="ql-indent" value="-1"></button>
                                    <button class="ql-indent" value="+1"></button>
                                </span>
                                <span class="ql-formats">
                                    <button class="ql-direction" value="rtl"></button>
                                    <select class="ql-align"></select>
                                </span>
                                {{-- <span class="ql-formats">
                                    <button class="ql-link"></button>
                                    <button class="ql-image"></button>
                                    <button class="ql-video"></button>
                                    <button class="ql-formula"></button>
                                </span> --}}
                            </div>
                            <div data-name="message" id="message"
                                data-placeholder="{{ Translator::phrase("message") }}"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
