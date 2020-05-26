<script>
    var pages = {
        base : "{{config('pages.base')}}",
        base_url : "{{config('pages.base_url')}}",
        color : "",
        init : () => {
            pages.load();
            pages.event.language.init();
            pages.event.translate.init();
            pages.event.qualiftion.init();
            pages.event.address();
            pages.event.staff.init();
            pages.event.student.init();
            pages.event.login.init();
            pages.event.color.init();
            pages.event.general.init();
        },
        load :()=>{
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var sessionForms = {!! Cookie::get('forms') ? Cookie::get('forms') : json_encode([]) !!};
            if(sessionForms){
                sessionForms.map(({form})=>{
                   if(Object.keys(form.fields).length){
                    Object.keys(form.fields).map((key)=>{
                            var e;
                            if(form.id){
                                e = $('form[id="'+form.id+'"]');
                            }else{
                                e = $('form[class="'+form.class+'"]');
                            }
                            if(e.find('[name="'+key+'"]').length){
                                if(e.find('[name="'+key+'"]')[0].tagName == "INPUT"){
                                    e.find('[name="'+key+'"]').val(form.fields[key]);
                                    if( e.find('[name="'+key+'"]').attr("type").toLowerCase() == "radio"){
                                        e.find('[id="'+form.fields[key]+'"]')[0].checked = true;
                                    }
                                }else if(e.find('[name="'+key+'"]')[0].tagName == "SELECT"){
                                    e.find('[name="'+key+'"]').val(form.fields[key]).attr("data-select-value",form.fields[key]).trigger("change");
                                }

                            }

                    })
                   }
                });
            }
        },
        event : {
            general : {
                init : () =>{
                    pages.event.general.update();
                },
                load : () =>{},
                update : () =>{
                    var a = {!! array_key_exists("config_general",$request_field['attributes']) ? json_encode($request_field['attributes']["config_general"]) : json_encode([])!!},
                                r = {!! array_key_exists("config_general",$request_field['rules']) ? json_encode($request_field['rules']["config_general"]) : json_encode([])!!},
                                m = {!! array_key_exists("config_general",$request_field['messages']) ? json_encode($request_field['messages']["config_general"]) : json_encode([])!!};


                    var form = $('form[id="form-general"]');
                        form.length &&
                        form.each(function(){
                            $(this).find("input[required]").on("input",function(){
                                var key = $(this).attr("name"),
                                    value = $(this).val(),
                                    fields = { [key]: value };
                                    pages.event.validation.validate(fields,$(this).parents("form"),{a,r,m});
                           }).removeAttr("required");

                            $(this).on("submit",function(e){
                                e.preventDefault();
                                var fields = $(this)
                                                .serializeArray()
                                                .reduce(function(obj, item) {
                                                    obj[item.name] = item.value;
                                                    return obj;
                                                }, {});
                                    if(pages.event.validation.validate(fields,this,{a,r,m})){
                                        $.ajax({
                                            url : pages.base_url+"/settings/general/update",
                                            method : "POST",
                                            headers: {
                                                'api-key': pages.key
                                            },
                                            data : new FormData(e.target),
                                            processData: false,
                                            contentType: false,
                                            success : (response) =>{
                                                if(response.success){
                                                    var data = response.data;
                                                }
                                            },
                                            error : (xhr, ajaxOptions, thrownError) =>{
                                                //console.log(xhr,ajaxOptions,thrownError);
                                              //  pages.event.color.load();
                                            }


                                        });
                                    }

                            });
                        });
                },
            },
            color : {
                targetContainer : $("#target_colors"),
                init : () => {
                    pages.event.color.load();
                },
                load : () => {
                    $.ajax({
                            url : pages.base_url+"/settings/color",
                            method : "POST",
                            success : (response) =>{
                               if(response.success){
                                   var data = response.data;
                                   if(pages.event.color.targetContainer.length){
                                    data.map(function({id,name,color}){
                                        var container = $("<div></div>").attr({
                                                    class : "col-lg-4 col-md-6"
                                            }),
                                            colorSwatch = $("<div></div>").attr({
                                                    class : "color-swatch"
                                            });
                                            colorHeader = $("<div></div>").attr({
                                                    class : "color-swatch-header bg-" + name
                                            }),
                                            colorSelected = $("<div></div>").attr({
                                                    class : "selected"
                                            }).html('<div class="selected-item-wrap"><i class="fas fa-check"></i></div>');

                                            colorHeader.on("click",function(e){
                                                e.preventDefault();
                                                if(name == pages.color){
                                                        return false;
                                                }else{
                                                    pages.event.color.update(name , this, colorSelected);
                                                }
                                            }).appendTo(colorSwatch);
                                            if(name == pages.color){
                                                colorSelected.appendTo(colorHeader);
                                            };

                                            colorSwatch.appendTo(container);
                                            container.appendTo(pages.event.color.targetContainer);
                                    });
                                   }

                               }
                            },
                            error : (xhr, ajaxOptions, thrownError) =>{
                                //console.log(xhr,ajaxOptions,thrownError);
                                if(pages.event.color.targetContainer.length){
                                    pages.event.color.load();
                                }
                            }
                        });

                },
                update :(name,targetSelected , checked) =>{
                    $.ajax({
                            url : pages.base_url+"/settings/color/update",
                            method : "POST",
                            headers: {
                                'api-key': pages.key
                            },
                            data : {color_name :name },
                            success : (response) =>{
                               if(response.success){
                                    $('[class*="-'+pages.color+'"]').each(function(){
                                        if($(this).parent().attr("class") == "color-swatch"){
                                            $(this).html("");
                                        }else{
                                            var getClass =  $(this).attr("class"),
                                                setClass = getClass.replace(pages.color,name);
                                            $(this).attr("class",setClass);
                                        }
                                    });
                                    pages.color = name;
                                    console.log(targetSelected);
                                    checked.appendTo(targetSelected);

                                    swal({
                                        title: response.message.title,
                                        text: response.message.text,
                                        type: "success",
                                        buttonsStyling: !1,
                                        confirmButtonClass:
                                        "btn btn-primary",
                                        confirmButtonText:
                                        response.message.button
                                        .confirm
                                    });
                               }
                            },
                            error : (xhr, ajaxOptions, thrownError) =>{
                                //console.log(xhr,ajaxOptions,thrownError);
                               // pages.event.color.update(name);
                            }
                        });

                }
            },
            login : {
                init : () =>{
                    var form = $('form[id="form-login"]');
                        form.length &&
                        form.each(function(){
                            var a = {!! array_key_exists("login",$request_field['attributes']) ? json_encode($request_field['attributes']["login"]) : json_encode([])!!},
                                r = {!! array_key_exists("login",$request_field['rules']) ? json_encode($request_field['rules']["login"]) : json_encode([])!!},
                                m = {!! array_key_exists("login",$request_field['messages']) ? json_encode($request_field['messages']["login"]) : json_encode([])!!};


                            $(this).on("submit",function(e){
                                e.preventDefault();
                                var fields = $(this)
                                                .serializeArray()
                                                .reduce(function(obj, item) {
                                                    obj[item.name] = item.value;
                                                    return obj;
                                                }, {});

                                    if(pages.event.validation.validate(fields,this,{a,r,m})){
                                        $.ajax({
                                            url : pages.base+"/login",
                                            method : "POST",
                                            headers: {
                                                'api-key': pages.key
                                            },
                                            data : new FormData(e.target),
                                            processData: false,
                                            contentType: false,

                                            success : (response) =>{
                                                if(response.success){
                                                    location.assign(response.redirct);
                                                }else{
                                                    $.notify(
                                                            {
                                                                // options
                                                                icon: "fas fa-times",
                                                                title: response.message.title,
                                                                message: response.message.text,
                                                                url: "#",
                                                                target: "_blank"
                                                            },
                                                            {
                                                                // settings
                                                                element: "body",
                                                                position: null,
                                                                type: "default",
                                                                allow_dismiss: true,
                                                                newest_on_top: false,
                                                                showProgressbar: false,
                                                                placement: {
                                                                    from: "top",
                                                                    align: "right"
                                                                },
                                                                offset: 20,
                                                                spacing: 10,
                                                                z_index: 1031,
                                                                delay: 5000,
                                                                timer: 1000,
                                                                url_target: "_blank",
                                                                mouse_over: null,
                                                                animate: {
                                                                    enter: "animated fadeInDown",
                                                                    exit: "animated fadeOutUp"
                                                                },
                                                                onShow: null,
                                                                onShown: null,
                                                                onClose: null,
                                                                onClosed: null,
                                                                icon_type: "class",
                                                                template:
                                                                    '<div data-notify="container" class="alert alert-dismissible alert-{0} alert-notify" role="alert"><span class="alert-icon" data-notify="icon"></span> <div class="alert-text"</div> <span class="alert-title" data-notify="title">{1}</span> <span data-notify="message">{2}</span></div><button type="button" class="close" data-notify="dismiss" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'
                                                            }
                                                        );
                                                }
                                            }
                                        });
                                    }

                            });
                        });
                },
            },
            language : {
                targetContainer : $("#target_language"),
                init : ()=>{
                        pages.event.language.load();
                        pages.event.language.add();
                },
                load : () =>{
                        var url = pages.base_url + "/language/lang";
                        $.ajax({
                            url : url,
                            method : "POST",

                            success : (response) =>{
                               if(response.success){
                                   var data = response.data;
                                   pages.event.language.locale(data);

                                   if(pages.event.language.targetContainer.length){
                                    var table = $("<table></table>"),
                                        thead = $("<thead></thead>"),
                                        tr = $("<tr></tr>"),
                                        th = $("<th></th>"),
                                        tbody = $("<tbody></tbody>");


                                        table.attr({
                                            class : "table table-hover table-bordered",
                                            id:"tbl_language"
                                        });
                                        th.html('{{Translator::phrase("numbering")}}').appendTo(tr);
                                        th = $("<th></th>");
                                        th.html('{{Translator::phrase("language")}}').appendTo(tr);
                                        th = $("<th></th>");
                                        th.html('{{Translator::phrase("language.code")}}').appendTo(tr);
                                        th = $("<th></th>");
                                        th.attr({width:80}).html("Action").appendTo(tr);
                                        tr.appendTo(thead);
                                        thead.appendTo(table);




                                    data.map(function({id,language,languageCode}){
                                        var tr = $("<tr></tr>"),
                                            td = $("<td></td>"),
                                            img =  $("<img>"),
                                            span = $("<span></span>");
                                            a = $("<a></a>");

                                            td.html(id).appendTo(tr);
                                            td = $("<td></td>");
                                            img.attr({
                                                class : "img-flag",
                                                style : "width: 26px;",
                                                src   :pages.base + "/assets/img/icons/flags/"+languageCode+".svg"
                                            }).appendTo(td);
                                            span.html(language).css({padding : 5}).appendTo(td);
                                            td.appendTo(tr);
                                            td = $("<td></td>");
                                            td.html(languageCode).appendTo(tr);
                                            td = $("<td></td>");


                                            a.attr({
                                                href :pages.base+"/language/delete/lang/"+languageCode,
                                                class :"btn btn-danger btn-sm remove-key"
                                            }).html('{{Translator::phrase("delete")}}').on("click",function(e){
                                                e.preventDefault();
                                                pages.event.language.delete(pages.base_url+"/language/lang/delete/"+languageCode, languageCode,tr);
                                            })
                                            .css({
                                                visibility : (languageCode == "en" || languageCode == "km") ? "hidden" : "visible"
                                            })
                                            .appendTo(td);

                                            td.appendTo(tr);
                                            tr.appendTo(tbody);
                                    });
                                    tbody.appendTo(table);
                                    pages.event.language.targetContainer.html(table);
                                   }
                               }
                            },
                            error : (xhr, ajaxOptions, thrownError) =>{
                                //console.log(xhr,ajaxOptions,thrownError);
                                if(pages.event.language.targetContainer.length){
                                 pages.event.language.load();
                                }
                            }

                        });
                },
                add : () =>{
                    var a = {!! array_key_exists("language",$request_field['attributes']) ? json_encode($request_field['attributes']["language"]) : json_encode([])!!},
                                r = {!! array_key_exists("language",$request_field['rules']) ? json_encode($request_field['rules']["language"]) : json_encode([])!!},
                                m = {!! array_key_exists("language",$request_field['messages']) ? json_encode($request_field['messages']["language"]) : json_encode([])!!};
                    var form = $('form[id="form-lang"]');
                        form.length &&
                        form.each(function(){
                            $(this).find("select[name]").each(function(){
                                $(this).val($(this).attr("data-select-value") ? $(this).attr("data-select-value").split(","): 0)
                                    .trigger("change")
                                    .removeAttr("required")
                                    .select2({
                                        templateResult: (item) =>{
                                            if (!item.id) {
                                                return item.text;
                                            }
                                            var countryUrl = pages.base+"/assets/img/icons/flags/";
                                            var img = $("<img>", {
                                                class: "img-flag",
                                                width: 26,
                                                src: countryUrl + item.element.value.toLowerCase() + ".svg"
                                            });
                                            var span = $("<span>", {
                                                text: " " + item.text
                                            });
                                            span.prepend(img);
                                        return span;
                                    }
                                }).on("select2:select", function(e) {
                                    $('#selected-flag').html( '<i class="flag '+e.params.data.id+'">');
                                    $('[name="lang_tl_key"]').val(e.params.data.text);
                                    $(this)
                                        .removeClass("has-error")
                                        .parent()
                                        .find(".invalid-feedback")
                                        .remove();
                                    $(this)
                                        .parent()
                                        .find(".select2-selection")
                                        .removeClass("has-error")
                                        .addClass("has-success");
                                });
                            });


                            $(this).on("submit",function(e){
                                e.preventDefault();
                                var fields = $(this)
                                                .serializeArray()
                                                .reduce(function(obj, item) {
                                                    obj[item.name] = item.value;
                                                    return obj;
                                                }, {});

                                if(pages.event.validation.validate(fields,this,{a,r,m})){
                                    $.ajax({
                                            url : pages.base_url+"/language/lang/add/save",
                                            method : "POST",
                                            headers: {
                                                'api-key': pages.key
                                            },
                                            data : new FormData(e.target),
                                            processData: false,
                                            contentType: false,

                                            success : (response) =>{
                                                if(response.success){
                                                    // Reset Form
                                                    this.reset();
                                                    $lang =  $(this).find("select[name]").val();
                                                    $(this).find("option[value="+$lang +"]").attr({
                                                        disabled : "disabled"
                                                    });
                                                    $('#selected-flag').html( '<i class="fas fa-flag">');
                                                    $(this).find("select[name]")
                                                    .select2("destroy")
                                                    .val(0)
                                                    .trigger("change")
                                                    .removeAttr("required")
                                                    .select2({
                                                        templateResult: (item) =>{
                                                            if (!item.id) {
                                                                return item.text;
                                                            }
                                                            var countryUrl = "/assets/img/icons/flags/";
                                                            var img = $("<img>", {
                                                                class: "img-flag",
                                                                width: 26,
                                                                src: countryUrl + item.element.value.toLowerCase() + ".svg"
                                                            });
                                                            var span = $("<span>", {
                                                                text: " " + item.text
                                                            });
                                                            span.prepend(img);
                                                        return span;
                                                    }
                                                }).on("select2:select", function(e) {
                                                    $('#selected-flag').html( '<i class="flag '+e.params.data.id+'">');
                                                    $('[name="lang_tl_key"]').val(e.params.data.text);
                                                    $(this)
                                                        .removeClass("has-error")
                                                        .parent()
                                                        .find(".invalid-feedback")
                                                        .remove();
                                                    $(this)
                                                        .parent()
                                                        .find(".select2-selection")
                                                        .removeClass("has-error")
                                                        .addClass("has-success");
                                                });


                                                    pages.event.language.load();
                                                    pages.event.translate.load();
                                                }
                                            }
                                    });
                                }
                            })

                        });
                },
                update : () =>{},
                locale : (languages) => {
                     // Nav Language ;
                    var current_lang =  $("<a></a>").attr({
                                            href : "#",
                                            class : "nav-link dropdown-toggle",
                                            "data-toggle":"dropdown",
                                            id:"navbarDropdownMenuLink2"
                                        }),
                        current_lang_flag = $("<img>").attr({
                                        src : pages.base + "/assets/img/icons/flags/{{app()->getLocale()}}.svg"
                                        }).css({width:26}).appendTo(current_lang),
                        list_lang = $("<ul></ul>").attr({
                            class : "dropdown-menu dropdown-menu-right py-0 overflow-hidden",
                            "aria-labelledby":"navbarDropdownMenuLink2",
                        });

                        languages.map(function({id,language,languageCode}){
                             // Nav Language ;
                             var li = $("<li></li>")
                                                li_a = $("<a></a>").attr({
                                                    class : 'dropdown-item',
                                                    href:pages.base + "/language/set/"+languageCode
                                                }).on("click",function(e){
                                                    e.preventDefault();
                                                    pages.event.language.setLocale(languageCode);
                                                }).appendTo(li),
                                                li_image = $("<img>").attr({
                                                    src: pages.base +"/assets/img/icons/flags/"+languageCode+".svg"
                                                }).css({width:26}).appendTo(li_a),
                                                li_span =  $("<span></span>").html(language).appendTo(li_a);
                                                li_check = $("<li></li>").addClass("fas fa-check pull-right");
                                                if("{{app()->getLocale()}}" == languageCode){
                                                    li_check.appendTo(li_a);
                                                }
                                                li.appendTo(list_lang);
                        });

                        $("#target_nav_language").html(current_lang);
                        list_lang.appendTo($("#target_nav_language"));
                },
                setLocale :(languageCode) =>{
                    var toObject = [];
                    if("{{app()->getLocale()}}" != languageCode){
                        var form = $("form");
                            form.length &&
                            form.each(function(){
                                var fields = $(this)
                                            .serializeArray()
                                            .reduce(function(obj, item) {
                                                obj[item.name] = item.value;
                                                return obj;
                                            }, {});

                                toObject.push({form: {id : $(this).attr("id") ,class: $(this).attr("class"),fields : fields} });
                            });

                            var formData = new FormData();
                                formData.append("forms",JSON.stringify(toObject));
                            $.ajax({
                                            url : pages.base+"/language/"+languageCode,
                                            method : "POST",
                                            headers: {
                                                'api-key': pages.key
                                            },
                                            data : formData,
                                            processData: false,
                                            contentType: false,
                                            success : (response) =>{
                                                if(response.success){
                                                   location.assign(response.redirect);
                                                }
                                            }
                            });

                    }
                },
                delete : (url,languageCode, targetRemove) =>{
                    $.get(url)
                        .done((data) =>{
                            swal({
                                title: data.message.title,
                                text: data.message.text,
                                type: "warning",
                                showCancelButton: !0,
                                buttonsStyling: !1,
                                confirmButtonClass: "btn btn-danger",
                                confirmButtonText: data.message.button.confirm,
                                cancelButtonClass: "btn btn-secondary",
                                cancelButtonText: data.message.button.cancel
                            }).then(e => {
                                if (e.value) {
                                    var formData = new FormData();
                                    formData.append("languageCode", languageCode);
                                    $.ajax({
                                        url: url,
                                        method: "POST",
                                        data: formData,
                                        processData: false,
                                        contentType: false,
                                        success: function(data) {
                                            if (data.success) {
                                                targetRemove.remove();
                                                swal({
                                                    title: data.message.title,
                                                    text: data.message.text,
                                                    type: "success",
                                                    buttonsStyling: !1,
                                                    confirmButtonClass:
                                                        "btn btn-primary",
                                                    confirmButtonText:
                                                        data.message.button
                                                            .confirm
                                                });


                                                $('form[id="form-lang"]').find("option[value="+languageCode +"]").removeAttr("disabled");
                                                $('form[id="form-lang"]').find("select[name]")
                                                    .select2("destroy")
                                                    .val(0)
                                                    .trigger("change")
                                                    .removeAttr("required")
                                                    .select2({
                                                        templateResult: (item) =>{
                                                            if (!item.id) {
                                                                return item.text;
                                                            }
                                                            var countryUrl = "/assets/img/icons/flags/";
                                                            var img = $("<img>", {
                                                                class: "img-flag",
                                                                width: 26,
                                                                src: countryUrl + item.element.value.toLowerCase() + ".svg"
                                                            });
                                                            var span = $("<span>", {
                                                                text: " " + item.text
                                                            });
                                                            span.prepend(img);
                                                        return span;
                                                    }
                                                }).on("select2:select", function(e) {
                                                    $('#selected-flag').html( '<i class="flag '+e.params.data.id+'">');
                                                    $('[name="lang_tl_key"]').val(e.params.data.text);
                                                    $(this)
                                                        .removeClass("has-error")
                                                        .parent()
                                                        .find(".invalid-feedback")
                                                        .remove();
                                                    $(this)
                                                        .parent()
                                                        .find(".select2-selection")
                                                        .removeClass("has-error")
                                                        .addClass("has-success");
                                                });


                                                pages.event.language.load();
                                                pages.event.translate.load();
                                            }else{
                                                swal({
                                                title: data.message.title,
                                                html:
                                                    data.errors +
                                                    "<br>" +
                                                    data.message.text,
                                                type: "error",
                                                buttonsStyling: !1,
                                                confirmButtonClass:
                                                    "btn btn-warning",
                                                confirmButtonText:
                                                    data.message.button.confirm
                                            });
                                            }
                                        },
                                        error: function(erorr) {
                                            var data = erorr.responseJSON;
                                            swal({
                                                title: data.message.title,
                                                html:
                                                    data.errors +
                                                    "<br>" +
                                                    data.message.text,
                                                type: "error",
                                                buttonsStyling: !1,
                                                confirmButtonClass:
                                                    "btn btn-warning",
                                                confirmButtonText:
                                                    data.message.button.confirm
                                            });
                                        }
                                    });
                                }
                            });
                        })
                        .fail((error) =>{  var data = error.responseJSON;});
                },
            },
            translate : {
                targetContainer : $("#target_translate"),
                init : () =>{
                    if(pages.event.translate.targetContainer.length){
                        pages.event.translate.load();
                        pages.event.translate.add();
                        pages.event.translate.search();
                    }
                },
                load : (url = false) =>{
                      var url = url ? url : pages.base_url+"/language/translate/"+location.search;
                        $.ajax({
                            url : url,
                            method : "POST",

                            beforeSend : (jqXHR) => {

                                var c = $("<div></div>").css({
                                       "background-color" : "rgba(100, 149, 237, 0.09)",
                                        position: "absolute",
                                        height: "100%",
                                        width: "100%",
                                        "z-index": "1",
                                    }),
                                    cspinder = $("<div></div>").css({
                                        position: "absolute",
                                        top: "50%",
                                        left: "50%",
                                        transform: "translate(50%, 100%)",
                                    }),
                                    spinner = $("<div></div>").attr({
                                       class :"spinner-border spinner-3 text-light"
                                    }).appendTo(cspinder);
                                    cspinder.appendTo(c);
                                    pages.event.translate.targetContainer.prepend(c).css({
                                            overflow: "hidden",
                                            position: 'relative'
                                        });

                            },

                            success : (response) =>{

                               if(response.success){
                                    var data = response.data;
                                    pages.event.translate.pagination(response.pages);

                                   var table = $("<table></table>"),
                                        thead = $("<thead></thead>"),
                                        tr = $("<tr></tr>"),
                                        tbody = $("<tbody></tbody>");


                                        table.attr({
                                            class : "table table-hover table-bordered",
                                            id:"tbl_translate"
                                        });

                                    // Table Head
                                    var head = Object.values(data[0])[2];

                                    $.each(head,function(i){
                                        var th = $("<th></th>");
                                            th.html(head[i]).appendTo(tr);

                                    })

                                    var th = $("<th></th>");
                                        th.attr({width:80}).html("Action").appendTo(tr);

                                    tr.appendTo(thead);
                                    thead.appendTo(table);

                                    data.map(function({id,language}){
                                       var tr = $("<tr></tr>");
                                       for(var i in language){
                                           var td = $("<td></td>"),
                                                a = $("<a></a>");

                                            a.attr({
                                                href :"#",
                                                class :"translate",
                                                "data-title" :language[i].title,
                                                "data-type" :"text",
                                                "data-pk" :language[i].text.value,
                                                "data-phrase" :language[i].phrase,
                                                "data-url" : language[i].url.update,
                                            }).html(language[i].text.value).editable({
                                                params: function(params) {

                                                params.name = $(this).editable().data('phrase');
                                                return params;
                                                },
                                                validate: function(value) {
                                                    if($.trim(value) == '') {
                                                        return language[i].text.required;
                                                    }
                                                },
                                                success: function(response, newValue) {
                                                    if(!response){
                                                        $.ajax({
                                                            url : $(this).attr("data-url"),
                                                            method : "POST",
                                                            data : {
                                                                name : $(this).attr("data-phrase"),
                                                                value :newValue
                                                            },
                                                            success : (response) => {
                                                                if(response.success){
                                                                    $(this).removeClass("editable-unsaved");
                                                                }
                                                            }
                                                        });
                                                    }

                                                }
                                            });
                                            a.appendTo(td)
                                            td.appendTo(tr);

                                            if((language.length - 1)== i){

                                                var button_td = $("<td></td>"),
                                                button_a = $("<a></a>");

                                                button_a.attr({
                                                    href : language[i].url.delete,
                                                    class : "btn btn-danger btn-sm remove-key",
                                                }).html(language[i].text.delete).on("click",function(e){
                                                    e.preventDefault();
                                                    pages.event.translate.delete(pages.base_url +"/language/translate/delete/"+ id,id,tr);
                                                });
                                                button_a.appendTo(button_td);
                                                button_td.appendTo(tr);

                                            }
                                       }
                                       tr.appendTo(tbody);
                                       tbody.appendTo(table);
                                    });
                                    pages.event.translate.targetContainer.html(table).removeAttr("style");
                               }else{
                                pages.event.translate.targetContainer.find("div").remove();
                                pages.event.translate.targetContainer.find("tbody").html('<tr class="odd"><td style="padding: 1rem !important;text-align: center;color: red;" valign="top" colspan="7" class="dataTables_empty">"'+response.message+'"</td></tr>');
                                $("#pagination_translate").html('');
                               }
                            },
                            error : (xhr, ajaxOptions, thrownError) =>{
                                //console.log(xhr,ajaxOptions,thrownError);
                                if(pages.event.translate.targetContainer.length){
                                    pages.event.translate.load(url);
                                }


                            }

                        });
                },
                add : () => {
                    var form = $('form[id="form-phrase"]');
                        form.length &&
                        form.each(function(){
                            var a = {!! array_key_exists("translate",$request_field['attributes']) ? json_encode($request_field['attributes']["translate"]) : json_encode([])!!},
                                r = {!! array_key_exists("translate",$request_field['rules']) ? json_encode($request_field['rules']["translate"]) : json_encode([])!!},
                                m = {!! array_key_exists("translate",$request_field['messages']) ? json_encode($request_field['messages']["translate"]) : json_encode([])!!};

                            $(this).find("input[required]").on("input",function(){
                                var key = $(this).attr("name"),
                                    value = $(this).val(),
                                    fields = { [key]: value };
                                    pages.event.validation.validate(fields,$(this).parents("form"),{a,r,m});
                           }).removeAttr("required");

                            $(this).on("submit",function(e){
                                e.preventDefault();
                                    var fields = $(this)
                                                .serializeArray()
                                                .reduce(function(obj, item) {
                                                    obj[item.name] = item.value;
                                                    return obj;
                                                }, {});
                                    if(pages.event.validation.validate(fields,this,{a,r,m})){
                                        $.ajax({
                                            url : pages.base_url+"/language/translate/add/save",
                                            method : "POST",
                                            headers: {
                                                'api-key': pages.key
                                            },
                                            data : new FormData(e.target),
                                            processData: false,
                                            contentType: false,

                                            success : (response) =>{
                                                if(response.success){
                                                    // Reset Form
                                                    this.reset();
                                                    $(this).find("input[name]").removeClass("has-success");
                                                    var tr = $("<tr></tr>").css({background: "lavender"});
                                                    response.data.map(function({id,language}){
                                                        for(var i in language){
                                                            var td = $("<td></td>"),
                                                                    a = $("<a></a>");

                                                                a.attr({
                                                                    href :"#",
                                                                    class :"translate",
                                                                    "data-title" :language[i].title,
                                                                    "data-type" :"text",
                                                                    "data-pk" :language[i].text.value,
                                                                    "data-phrase" :language[i].phrase,
                                                                    "data-url" : language[i].url.update,
                                                                }).html(language[i].text.value).editable({
                                                                    params: function(params) {
                                                                    params.name = $(this).editable().data('phrase');
                                                                    return params;
                                                                    },
                                                                    validate: function(value) {
                                                                        if($.trim(value) == '') {
                                                                            return language[i].text.required;
                                                                        }
                                                                    },
                                                                    success: function(response, newValue) {
                                                                        if(!response){

                                                                            $.ajax({
                                                                                url : $(this).attr("data-url"),
                                                                                method : "POST",
                                                                                data : {
                                                                                    name : $(this).attr("data-phrase"),
                                                                                    value :newValue
                                                                                },
                                                                                success : (response) => {
                                                                                    if(response.success){
                                                                                        $(this).removeClass("editable-unsaved");
                                                                                    }
                                                                                }
                                                                            });
                                                                        }

                                                                    }
                                                                });
                                                                a.appendTo(td)
                                                                td.appendTo(tr);

                                                                if((language.length - 1)== i){

                                                                    var button_td = $("<td></td>"),
                                                                    button_a = $("<a></a>");

                                                                    button_a.attr({
                                                                        href : language[i].url.delete,
                                                                        class : "btn btn-danger btn-sm remove-key",
                                                                    }).html(language[i].text.delete).on("click",function(e){
                                                                        e.preventDefault();
                                                                        pages.event.translate.delete(pages.base_url +"/language/translate/delete/"+ id,id,tr);
                                                                    });
                                                                    button_a.appendTo(button_td);
                                                                    button_td.appendTo(tr);

                                                                }
                                                        }
                                                    });
                                                    pages.event.translate.targetContainer.find("tbody").prepend(tr);

                                                } else if(Object.values(response.errors).length){
                                                    return pages.event.validation.highlight.error(response.errors, this);
                                                }else{
                                                    swal({
                                                            title: response.message.title,
                                                            text: response.message.text,
                                                            type: "warning",
                                                            showCancelButton: !0,
                                                            buttonsStyling: !1,
                                                            confirmButtonClass: "btn btn-primary",
                                                            confirmButtonText: response.message.button.confirm,
                                                            cancelButtonClass: "btn btn-secondary",
                                                            cancelButtonText: response.message.button.cancel
                                                        });
                                                }
                                            },
                                            error : (error) =>{
                                                 var  response =  error.responseJSON
                                                }
                                        });
                                    }

                            });
                        });
                },
                update : () =>{
                },
                delete : (url,id, targetRemove) =>{
                    $.get(url)
                        .done((data) =>{
                            swal({
                                title: data.message.title,
                                text: data.message.text,
                                type: "warning",
                                showCancelButton: !0,
                                buttonsStyling: !1,
                                confirmButtonClass: "btn btn-danger",
                                confirmButtonText: data.message.button.confirm,
                                cancelButtonClass: "btn btn-secondary",
                                cancelButtonText: data.message.button.cancel
                            }).then(e => {
                                if (e.value) {
                                    var formData = new FormData();
                                    formData.append("id", id);
                                    $.ajax({
                                        url: url,
                                        method: "POST",
                                        data: formData,
                                        processData: false,
                                        contentType: false,

                                        success: function(data) {
                                            if (data.success) {
                                                targetRemove.remove();
                                                swal({
                                                    title: data.message.title,
                                                    text: data.message.text,
                                                    type: "success",
                                                    buttonsStyling: !1,
                                                    confirmButtonClass:
                                                        "btn btn-primary",
                                                    confirmButtonText:
                                                        data.message.button
                                                            .confirm
                                                });
                                            }else{
                                                swal({
                                                title: data.message.title,
                                                html:
                                                    data.errors +
                                                    "<br>" +
                                                    data.message.text,
                                                type: "error",
                                                buttonsStyling: !1,
                                                confirmButtonClass:
                                                    "btn btn-warning",
                                                confirmButtonText:
                                                    data.message.button.confirm
                                            });
                                            }
                                        },
                                        error: function(erorr) {
                                            var data = erorr.responseJSON;
                                            swal({
                                                title: data.message.title,
                                                html:
                                                    data.errors +
                                                    "<br>" +
                                                    data.message.text,
                                                type: "error",
                                                buttonsStyling: !1,
                                                confirmButtonClass:
                                                    "btn btn-warning",
                                                confirmButtonText:
                                                    data.message.button.confirm
                                            });
                                        }
                                    });
                                }
                            });
                        })
                        .fail((error) =>{  var data = error.responseJSON;});
                },
                pagination : (paginate) => {

                    $("#pagination_translate").twbsPagination('destroy');
                    $("#pagination_translate").twbsPagination({
                        totalPages: paginate.totalPages,
                        visiblePages: 5,
                        //href: pages.base +'/language/?page=',
                        startPage: paginate.current_page,
                        prev : '<i class="fas fa-arrow-left"></i>',
                        next : '<i class="fas fa-arrow-right"></i>',
                        first: '',
                        last: '',
                    }).on('page', function (event, page) {

                        function getUrlParameter(name) {
                            name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
                            var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
                            var results = regex.exec(location.search);
                            return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
                        };

                        var url = pages.base_url +"/language/translate?page="+page;
                        if(getUrlParameter("search")){
                            url = pages.base_url +"/language/translate?search="+getUrlParameter("search")+"&page="+page;
                        }


                        if (typeof (history.pushState) != "undefined") {
                            var obj = { page: page, url: url };
                            history.pushState(obj, obj.page, obj.url);
                        } else {
                            alert("Browser does not support HTML5.");
                        }
                        pages.event.translate.load( getUrlParameter("search") ? pages.base_url +"/language/translate?search="+ getUrlParameter("search")+"&page="+page : pages.base_url +"/language/translate?page="+page);
                    });
                },
                search : (word = false) =>{
                    $('form#phrase_search').on('submit', function(e) {
                         e.preventDefault();
                         word = $(this).find('[name="search"]').val();
                            if(word){
                                var url = pages.base_url +"/language/translate?search="+word,page = word;
                                if (typeof (history.pushState) != "undefined") {
                                    var obj = { page: page, url: url };
                                    history.pushState(obj, obj.page, obj.url);
                                } else {
                                    alert("Browser does not support HTML5.");
                                }
                                pages.event.translate.load(pages.base_url +"/language/translate?search="+word);
                            }else{
                                pages.event.translate.load();
                            }
                    }).find('[name="search"]').on('input',function(){
                         word = $(this).val();
                        if($(this).val() == ''){

                            var url = pages.base_url +"/language/translate/",page = "language";
                                if (typeof (history.pushState) != "undefined") {
                                    var obj = { page: page, url: url };
                                    history.pushState(obj, obj.page, obj.url);
                                } else {
                                    alert("Browser does not support HTML5.");
                                }
                            pages.event.translate.load();
                        }else{
                            var url = pages.base_url +"/language/translate?search="+word,page = word;
                                if (typeof (history.pushState) != "undefined") {
                                    var obj = { page: page, url: url };
                                    history.pushState(obj, obj.page, obj.url);
                                } else {
                                    alert("Browser does not support HTML5.");
                                }
                               // pages.event.translate.load(pages.base +"/api/translate?search="+word);
                        };
                    });
                }

            },
            qualiftion : {
                targetContainer : $("#taget_experience"),
                init : ()=>{
                    if(pages.event.qualiftion.targetContainer.length){
                        pages.event.qualiftion.add();
                    }

                },
                add  : () =>{
                    var experience = {
                        class: 'form-control',
                        title: "{{ Translator::phrase('experience') }}",
                        placeholder: "{{ Translator::phrase('experience') }}",
                        id: "st",
                        name: "experience[]",
                        required: false,
                        multiple : 'multiple'
                    },
                    experience_info = {
                        class: 'form-control',
                        title: "{{ Translator::phrase('experience_info') }}",
                        placeholder: "{{ Translator::phrase('experience_info') }}",
                        id: "st",
                        name: "experience_info[]",
                        required: false,
                        multiple : 'multiple'
                    },
                    container = $('<div></div').attr({
                            class: "form-row"
                        }),
                    container_experience = $('<div></div').attr({
                            class: "col-md-4 mb-3"
                        }),
                    container_experience_info = $('<div></div').attr({
                            class: "col-md-5 mb-3"
                        }),
                    container_btn = $('<div></div').attr({
                            class: "col-md-3 mb-3"
                        }),
                    input = $('<input />'),
                    textarea = $('<textarea></textarea>'),
                    btn_add = $('<a></a>'),
                    btn_remove = $('<a></a>');

                    if (!pages.event.qualiftion.targetContainer.children().length) {
                           // pages.event.qualiftion.label();
                    }
                        input.attr({
                            class: experience.class,
                            title: experience.title,
                            placeholder: experience.placeholder,
                            'data-placeholder': experience.placeholder,
                            id: experience.id,
                            name: experience.name,
                            required: experience.required,
                            multiple : experience.multiple,
                        }).appendTo(container_experience);

                        textarea.attr({
                            class: experience_info.class,
                            title: experience_info.title,
                            placeholder: experience_info.placeholder,
                            'data-placeholder': experience_info.placeholder,
                            id: experience_info.id,
                            name: experience_info.name,
                            required: experience_info.required,
                            multiple : experience_info.multiple,
                        }).css({
                            'min-height': '46px !important',
                            height: 46,
                        }).appendTo(container_experience_info);


                        btn_add.attr({
                            class: 'btn btn-default',
                            href: '#'
                        }).html('<i class="fas fa-plus"></i>').click(function(e) {
                            e.preventDefault();
                            pages.event.qualiftion.add();
                            pages.event.qualiftion.targetContainer.children().find('.btn-danger').removeAttr('style');
                        }).appendTo(container_btn);


                        btn_remove.attr({
                            class: 'btn btn-danger',
                            href: '#'
                        }).html('<i class="fas fa-trash"></i>').click(function(e) {
                            e.preventDefault();
                            if (pages.event.qualiftion.targetContainer.children().length == 1) {
                                return false;
                            } else {
                                container.remove();
                                if (pages.event.qualiftion.targetContainer.children().length == 1) {
                                    pages.event.qualiftion.targetContainer.children().find('.btn-danger').css({
                                        visibility: 'hidden'
                                    });
                                }
                            }

                        }).css({
                            visibility: pages.event.qualiftion.targetContainer.children().length ? 'visible' : 'hidden'
                        }).appendTo(container_btn);

                        container_experience.appendTo(container);
                        container_experience_info.appendTo(container);
                        container_btn.appendTo(container);
                        container.appendTo(pages.event.qualiftion.targetContainer);


                },

            },
            address :() => {
                    var e = $('[data-toggle-collapse]');
                        e.length &&
                        e.each(function(){
                            $(this).on('shown.bs.collapse', function () {
                                var dataCollapse = $(this).attr('data-toggle-collapse');
                                    $('[data-collapse="'+dataCollapse+'"]').hide(100);
                            }).on('hidden.bs.collapse', function () {
                                var dataCollapse = $(this).attr('data-toggle-collapse');
                                    $('[data-collapse="'+dataCollapse+'"]').show(100);
                            });
                        });
            },
            staff : {
                init : () => {
                    pages.event.staff.add();
                },
                add : () => {
                    var form = $('form[id="form-staff"]');
                        form.length &&
                        form.each(function(){

                            var a = {!! array_key_exists("staff",$request_field['attributes']) ? json_encode($request_field['attributes']["staff"]) : json_encode([])!!},
                                r = {!! array_key_exists("staff",$request_field['rules']) ? json_encode($request_field['rules']["staff"]) : json_encode([])!!},
                                m = {!! array_key_exists("staff",$request_field['messages']) ? json_encode($request_field['messages']["staff"]) : json_encode([])!!};

                                $(this).find("select[name]").each(function(){
                                    $(this).val($(this).attr("data-select-value") ? $(this).attr("data-select-value").split(","): 0)
                                    .trigger("change")
                                    .removeAttr("required")
                                    .on("select2:select", function(e) {
                                    $(this)
                                        .removeClass("has-error")
                                        .parent()
                                        .find(".invalid-feedback")
                                        .remove();
                                    $(this)
                                        .parent()
                                        .find(".select2-selection")
                                        .removeClass("has-error")
                                        .addClass("has-success");
                                    });
                                 });




                            $(this).find("input[required]").on("input keydown",function(e){
                                var key = $(this).attr("name"),
                                    value = $(this).val(),
                                    fields = { [key]: value };
                                    pages.event.validation.validate(fields,$(this).parents("form"),{a,r,m});
                           }).removeAttr("required");

                            $(this).on("submit",function(e){
                                e.preventDefault();
                                var fields = $(this)
                                                .serializeArray()
                                                .reduce(function(obj, item) {
                                                    obj[item.name] = item.value;
                                                    return obj;
                                                }, {});

                                if(pages.event.validation.validate(fields,this,{a,r,m})){
                                    $.ajax({
                                            url : pages.base+"/super-admin/teacher/add/save",
                                            method : "POST",
                                            headers: {
                                                'api-key': pages.key
                                            },
                                            data : new FormData(e.target),
                                            processData: false,
                                            contentType: false,

                                            success : (response) =>{
                                                if(response.success){
                                                    this.reset();

                                                   $(this).find('[name="gender"]').each(function(){
                                                       this.checked = false;
                                                   });
                                                   $(this).find("select[name]").each(function(){
                                                        $(this).select2("destroy");
                                                        $(this)
                                                        .val(0)
                                                            .trigger("change")
                                                            .on("select2:select", function(e) {
                                                                $(this)
                                                                    .removeClass("has-error")
                                                                    .parent()
                                                                    .find(".invalid-feedback")
                                                                    .remove();
                                                                $(this)
                                                                    .parent()
                                                                    .find(".select2-selection")
                                                                    .removeClass("has-error")
                                                                    .addClass("has-success");
                                                            });
                                                   });



                                                   swal({
                                                        title: response.message.title,
                                                        text: response.message.text,
                                                        type: "success",
                                                        buttonsStyling: !1,
                                                        confirmButtonClass: "btn btn-success",
                                                        confirmButtonText: response.message.button.confirm,
                                                        onClose: () => {
                                                            $(".modal#add_new_modal").modal("hide");
                                                        }
                                                    });
                                                }else{
                                                    pages.event.validation.validate(response.errors,this,{a,r,m});
                                                }
                                            }
                                    });
                                }
                            });
                        });
                },
            },
            student : {
                init : () => {
                    pages.event.student.add();
                },
                add : () => {
                    var form = $('form[id="form-student"]');
                        form.length &&
                        form.each(function(){

                            var a = {!! array_key_exists("student",$request_field['attributes']) ? json_encode($request_field['attributes']["student"]) : json_encode([])!!},
                                r = {!! array_key_exists("student",$request_field['rules']) ? json_encode($request_field['rules']["student"]) : json_encode([])!!},
                                m = {!! array_key_exists("student",$request_field['messages']) ? json_encode($request_field['messages']["student"]) : json_encode([])!!};

                                $(this).find("select[name]").each(function(){
                                    $(this).val($(this).attr("data-select-value") ? $(this).attr("data-select-value").split(","): 0)
                                    .trigger("change")
                                    .removeAttr("required")
                                    .on("select2:select", function(e) {
                                    $(this)
                                        .removeClass("has-error")
                                        .parent()
                                        .find(".invalid-feedback")
                                        .remove();
                                    $(this)
                                        .parent()
                                        .find(".select2-selection")
                                        .removeClass("has-error")
                                        .addClass("has-success");
                                    });
                                 });




                            $(this).find("input[required]").on("input keydown",function(e){
                                var key = $(this).attr("name"),
                                    value = $(this).val(),
                                    fields = { [key]: value };
                                    pages.event.validation.validate(fields,$(this).parents("form"),{a,r,m});
                           }).removeAttr("required");

                            $(this).on("submit",function(e){
                                e.preventDefault();
                                var fields = $(this)
                                                .serializeArray()
                                                .reduce(function(obj, item) {
                                                    obj[item.name] = item.value;
                                                    return obj;
                                                }, {});

                                if(pages.event.validation.validate(fields,this,{a,r,m})){
                                    $.ajax({
                                            url : pages.base+"/super-admin/student/add/save",
                                            method : "POST",
                                            headers: {
                                                'api-key': pages.key
                                            },
                                            data : new FormData(e.target),
                                            processData: false,
                                            contentType: false,

                                            success : (response) =>{
                                                if(response.success){
                                                    this.reset();

                                                   $(this).find('[name="gender"]').each(function(){
                                                       this.checked = false;
                                                   });
                                                   $(this).find("select[name]").each(function(){
                                                        $(this).select2("destroy");
                                                        $(this)
                                                        .val(0)
                                                            .trigger("change")
                                                            .on("select2:select", function(e) {
                                                                $(this)
                                                                    .removeClass("has-error")
                                                                    .parent()
                                                                    .find(".invalid-feedback")
                                                                    .remove();
                                                                $(this)
                                                                    .parent()
                                                                    .find(".select2-selection")
                                                                    .removeClass("has-error")
                                                                    .addClass("has-success");
                                                            });
                                                   });



                                                   swal({
                                                        title: response.message.title,
                                                        text: response.message.text,
                                                        type: "success",
                                                        buttonsStyling: !1,
                                                        confirmButtonClass: "btn btn-success",
                                                        confirmButtonText: response.message.button.confirm,
                                                        onClose: () => {
                                                            $(".modal#add_new_modal").modal("hide");
                                                        }
                                                    });
                                                }else{
                                                    pages.event.validation.validate(response.errors,this,{a,r,m});
                                                }
                                            }
                                    });
                                }
                            });
                        });
                },
            },
            validation : {
                validate : (fields, form , {a,r,m}) =>{
                    Validator.useLang('{{app()->getLocale()}}');
                    var validation = new Validator(fields,r,m);
                        validation.setAttributeNames(a);
                        if (validation.fails()) {
                            if (Object.keys(fields).length == 1) {
                                var key = Object.keys(fields),
                                    msg = validation.errors.get(Object.keys(fields)),
                                    toObject = { [key]: msg };
                                 pages.event.validation.highlight.error(toObject, form);
                            }else{
                                pages.event.validation.highlight.error(validation.errors.errors,form);
                            }
                            return false;
                        }else{
                            return true;
                        }
                },
                highlight : {
                    error : (fields ,form) =>{
                        for (error in fields) {
                            $(form).find("#has-error-for-" + error.replace("[", "").replace("]", "")).remove();
                            if (fields[error].length === 0) {
                                return pages.event.validation.highlight.success(fields, form);
                            }
                            var msg = $("<div></div>").attr({
                                        class : "invalid-feedback",
                                        id :"has-error-for-" + error.replace("[", "").replace("]", ""),
                                    }).css("display", "block").text(fields[error]);

                           if (error === "gender") {
                                $(form)
                                .find('[name="' + error + '"]')
                                .addClass("has-error")
                                .parents('[id="gender"]')
                                .addClass("has-error")
                                .after(msg);
                            }else if($('[name="' + error + '"]').parent().hasClass("dz-preview")){
                                $(form)
                                .find('[name="' + error + '"]')
                                .addClass("has-error")
                                .parent()
                                .parent()
                                .addClass("has-error")
                                .after(msg);
                            }else if ($('[name="' + error + '"]')[0].tagName === "SELECT") {
                                $(form)
                                .find('[name="' + error + '"]')
                                .addClass("has-error")
                                .parent()
                                .append(msg)
                                .parent()
                                .find(".select2-selection")
                                .addClass("has-error");
                            }else{
                                $(form).find('[name="' + error + '"]')
                                .addClass("has-error")
                                .after(msg);
                            }



                        }
                    },
                    success : (fields,form) =>{
                        $(form)
                        .find('[name="' + Object.keys(fields) + '"]')
                        .removeClass("has-error")
                        .addClass("has-success");
                        if (fields.length) {
                            for (var error in fields) {
                                $(form).find("#has-error-for-" + error.replace("[", "").replace("]", "")).remove();

                                if (error === "gender") {
                                    $(form)
                                        .find('[name="' + error + '"]')
                                        .removeClass("has-error")
                                        .parents('[id="gender"]').removeClass("has-error").addClass('has-success');

                                }else if($('[name="' + error + '"]').parent().hasClass("dz-preview")){
                                        $(form)
                                        .removeClass('[name="' + error + '"]')
                                        .addClass("has-success")
                                        .parent()
                                        .parent()
                                        .addClass("has-success");

                                }else if ($('[name="' + error + '"]')[0].tagName === "SELECT" ) {
                                    $(form)
                                        .find('[name="' + error + '"]')
                                        .removeClass("has-error")
                                        .addClass("has-success")
                                        .parent()
                                        .find(".select2-selection")
                                        .removeClass("has-error")
                                        .addClass("has-success");
                                } else {
                                    $(form)
                                        .find('[name="' + error + '"]')
                                        .removeClass("has-error")
                                        .addClass("has-success");
                                }
                            }
                        }
                    }
                }
            }
        },
    };
    pages.init();

</script>







{{-- <script>
    $(".nav-item a.nav-link").on("click", function(e) {
        e.preventDefault();
        var url = $(this).attr("href");
        $.get(url).done(function(data) {
            var newDocument = new DOMParser().parseFromString(data,"text/html");
            //$("head").html($(newDocument).find("head").html());
            $("body").html($(newDocument).find("body").html());
        });
    });
</script> --}}
