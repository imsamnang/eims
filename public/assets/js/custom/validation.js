(function (old) {
    $.fn.attr = function () {
        if (arguments.length === 0) {
            if (this.length === 0) {
                return null;
            }

            var obj = {};
            $.each(this[0].attributes, function () {
                if (this.specified) {
                    obj[this.name] = this.value;
                }
            });
            return obj;
        }

        return old.apply(this, arguments);
    };
})($.fn.attr);

(function ($) {
    $.fn.validation = function (options = {}) {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
        });
        var defaults = {
                url: $(this).attr("action"),
                method: "POST",
                containerScroll: $(this).parents('.modal').length ? $(this).parents('.modal').find('.modal-body') : null,
                onBeforeSend: function (xhr, load) {},
                onSuccess: function (response, load) {},
                onError: function (xhr, type, error, load) {},
                onSubmit: function (event, validation, load) {},
                onValidate: function (validation, load) {},
                request_field: {},
                lang: $("html[lang]").attr("lang")
            },
            newRules = null,
            settings = $.extend(defaults, options),
            a = settings.request_field.attributes,
            r = settings.request_field.rules,
            m = settings.request_field.messages,
            load = $('<span class="loading ml-2"><img src="' + location.origin + '/assets/img/icons/LOOn0JtHNzb.gif"></span>'),
            ajax = () => {
                $.ajax({
                    url: settings.url,
                    method: settings.method,
                    data: settings.data,
                    processData: false,
                    contentType: false,
                    beforeSend: xhr => {
                        options.hasOwnProperty("onBeforeSend") && "function" == typeof options.onBeforeSend ? settings.onBeforeSend(xhr, load) : onBeforeSend(xhr, load);
                    },
                    success: response => {
                        options.hasOwnProperty("onSuccess") && "function" == typeof options.onSuccess ? settings.onSuccess(response, load) : onSuccess(response, load);
                    },
                    error: (xhr, type, error) => {
                        options.hasOwnProperty("onError") && "function" == typeof options.onError ? settings.onError(xhr, type, error) : onError(xhr, type, error, load);
                    }
                });
            },
            onBeforeSend = (xhr, load) => {
                if ($(this).parents(".modal").length) {
                    $(this).parents(".modal").find("button#btn-submit").append(load);
                } else {
                    $(this).find('[type="submit"]').append(load);
                }
                Swal({
                    title: $(this).attr('role') == 'add' ? sweetalert2Locale.__('Saving data') : sweetalert2Locale.__('Updating data'),
                    showCloseButton: true,
                    allowOutsideClick: false,
                    onBeforeOpen: () => {
                        Swal.showLoading();
                    },
                    onClose: () => {
                        if (ajax && ajax.abort) {
                            ajax.abort();
                        }

                    }
                });
            },
            onSuccess = (response, load) => {
                load.remove();
                if (response.success) {
                    var rowNode = [];
                    if (response.type == "add") {

                        if ($(this).parents(".modal").length) {
                            $(this).parents(".modal").modal("hide");
                        } else {
                            $(this).find(".invalid-feedback").remove();
                            $(this).find(".has-error").removeClass("has-error");
                            $(this).find(".has-success").removeClass("has-success");
                            $(this).get(0).reset();
                            $(this).find("select").val(null).trigger("change");
                        }
                        if (response.html) {
                            if ($('table#datatable-basic').length) {
                                var table = $('table#datatable-basic').dataTable() //not DataTable() function

                                $(response.html).each((i, element) => {
                                    if ($(element).find('td').length) {
                                        var _td = [];
                                        $(element).find('td').each(function () {
                                            _td.push($(this).html());
                                        });
                                        var _tr = table.api().row.add(_td);

                                        rowNode.push($(_tr.node()));

                                        var aiDisplayMaster = table.fnSettings()["aiDisplayMaster"];
                                        var irow = aiDisplayMaster.pop();
                                        aiDisplayMaster.unshift(irow);
                                        table.api().draw();

                                        //set attrs to tr
                                        $(_tr.node()).attr($(element).attr());

                                        //set attrs to td
                                        $(element).find('td').each(function (i) {
                                            $(_tr.node()).find('td').eq(i).attr($(this).attr());
                                        });
                                    }


                                });
                                $.getScript(location.origin + "/assets/js/argon.min.js");

                            }
                        }


                    } else if (response.type == "update") {
                        if (response.html) {
                            if ($('table#datatable-basic').length) {
                                $.each(response.data, (i, row) => {
                                    var $tr = $('table#datatable-basic').find('tr[data-id="' + row.id + '"]');
                                    rowNode.push($tr);
                                    $tr.find('td').each((j, old_td) => {
                                        /**
                                         * {j=0}  = td checkbox
                                         * {j=1}  = td id
                                         * {j+1 = td.length}  = last td
                                         */

                                        if (j == 0 || j == 1) {

                                        } else if ((j + 1) == $tr.find('td').length) {

                                        } else {
                                            var new_td = $(response.html).eq(i).find('td').eq(j);
                                            $(old_td).html(new_td.html());
                                        }
                                    });
                                });

                            }
                        }
                    }
                    Swal.fire({
                        type: 'success',
                        title: response.message.replace(/\n/g, "<br>"),
                        showConfirmButton: false,
                        showCloseButton: true,
                        allowOutsideClick: false,
                        timer: 2500,
                        onClose: () => {
                            if (response.reload) {
                                location.reload();
                            }

                            if (rowNode) {
                                $.each(rowNode, (i, tr) => {
                                    tr.css('backgroundColor', 'yellow')
                                        .animate({
                                            backgroundColor: 'unset'
                                        }, 'slow');
                                });
                            }

                        }
                    });
                } else if (response.hasOwnProperty("errors") && Object.values(response.errors).length) {
                    Swal.close();
                    validation.highlight.error(response.errors, this);
                } else {
                    swal({
                        showCloseButton: false,
                        title: sweetalert2Locale.__('Error'),
                        html: response.message.replace(/\n/g, "<br>"),
                        type: "warning",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-primary",
                        confirmButtonText: sweetalert2Locale.__('Ok'),
                        cancelButtonClass: "btn btn-secondary",
                        cancelButtonText: sweetalert2Locale.__('Cancel')
                    });
                }
            },
            onError = (xhr, type, error, load) => {
                load.remove();
                swal({
                    type: "error",
                    title: type,
                    text: error
                });
            },
            validation = {
                validate: (fields, form, newRules = null) => {
                    Validator.useLang(settings.lang);
                    $.each(fields, (key, value) => {
                        if ($.inArray("__" + key, Object.keys(r)) != -1 && value) {
                            if (r["__" + key] && JSON.parse(r["__" + key])[value]) {
                                newRules = $.extend({}, r, JSON.parse(r["__" + key])[value]);
                            } else {
                                newRules = r;
                            }
                        }
                    });
                    var validator = new Validator(fields, newRules ? newRules : r, m);
                    validator.setAttributeNames(a);
                    settings.onValidate(validator.errors);
                    if (validator.fails()) {
                        if (Object.keys(fields).length == 1) {
                            var key = Object.keys(fields),
                                msg = validator.errors.get(Object.keys(fields)),
                                toObject = {
                                    [key]: msg
                                };
                            validation.highlight.error(toObject, form);
                        } else {
                            validation.highlight.error(validator.errors.errors, form);
                        }
                        return false;
                    } else {
                        return true;
                    }
                },
                highlight: {
                    error: (fields, form) => {
                        for (error in fields) {
                            if (fields[error].length === 0) {
                                return validation.highlight.success(fields, form);
                            }

                            $(form).find('[name="' + error + '"]').parents('.collapse').addClass('show');
                            $(form).find('[name="' + error + '"]').parents('[data-toggle="collapse"]').removeAttr('style');

                            $(form).find('[name="' + error + '"]').removeClass("has-success").addClass("has-error");
                            var msg = $("<div></div>").attr({
                                class: "invalid-feedback",
                                id: "has-error-for-" + error.replace(/\[/g, "").replace(/\]/g, "")
                            }).css("display", "block").text(fields[error]);

                            if ($('[name="' + error + '"]').length) {
                                if ($('[name="' + error + '"]').parent().hasClass("dz-preview")) {
                                    $(form).find('[name="' + error + '"]').parent().find(".input-group-text").addClass("has-error");
                                    if ($(form).find('[name="' + error + '"]').addClass("has-error").parent().parent().parent().find('[id="has-error-for-' + error.replace(/\[/g, "").replace(/\]/g, "") + '"]').length == 0) {
                                        $(form).find('[name="' + error + '"]').addClass("has-error").parent().parent().addClass("has-error rounded-right").after(msg);
                                    }
                                } else if ($.trim($('[name="' + error + '"]').attr('type')).toLowerCase() === "radio") {
                                    if ($(form).find('[name="' + error + '"]').parents('.custom-control.custom-radio.custom-control-inline').parent().parent().find('[id="has-error-for-' + error.replace(/\[/g, "").replace(/\]/g, "") + '"]').length == 0) {
                                        $(form).find('[name="' + error + '"]').parents('.custom-control.custom-radio.custom-control-inline').parent().addClass("has-error").after(msg);
                                    }

                                } else if ($('[name="' + error + '"]')[0].tagName.toLocaleLowerCase() === "select") {
                                    $(form).find('[name="' + error + '"]').parent().find(".input-group-text").addClass("has-error");

                                    if ($(form).find('[name="' + error + '"]').parent().find('[id="has-error-for-' + error.replace(/\[/g, "").replace(/\]/g, "") + '"]').length == 0) {
                                        $(form).find('[name="' + error + '"]').addClass("has-error").parent().append(msg).find(".select2-selection").addClass($(form).find('[name="' + error + '"]').parent().find(".input-group-text").length ? "has-error rounded-left-0 rounded-right" : "has-error");
                                    }
                                } else {
                                    $(form).find('[name="' + error + '"]').find(".input-group-text").addClass("has-error");
                                    if ($(form).find('[name="' + error + '"]').parent().find('[id="has-error-for-' + error.replace(/\[/g, "").replace(/\]/g, "") + '"]').length == 0) {
                                        $(form).find('[name="' + error + '"]').addClass("has-error rounded-right").after(msg);
                                    }


                                }
                            }
                        }

                        var scroll = Object.keys(fields);
                        var scrollTo = $('<a name="scrollTo">');
                        if ($(this).find('[name="scrollTo"]').length == 0) {
                            $(this).append(scrollTo);
                        }
                        $(this).find('[name="scrollTo"]').attr("href", "#" + scroll[0].replace(/\[/g, "").replace(/\]/g, ""));
                        validation.scroll($(this).find('[name="scrollTo"]'), scroll[0].replace(/\[/g, "").replace(/\]/g, ""));
                    },
                    success: (fields, form) => {

                        $(form).find('[name="' + Object.keys(fields) + '"]').parent().find(".input-group-text").removeClass("has-error").addClass("has-success");
                        $(form).find('[name="' + Object.keys(fields) + '"]').removeClass("has-error").addClass("has-success");
                        for (var error in fields) {
                            $(form).find("#has-error-for-" + error.replace(/\[/g, "").replace(/\]/g, "")).remove();
                            if ($('[name="' + error + '"]').parent().hasClass("dz-preview")) {
                                // $(form).removeClass('[name="' + error + '"]').addClass("has-success").parent().parent().addClass("has-success");
                            } else if ($.trim($('[name="' + error + '"]').attr('type')).toLowerCase() === "radio") {
                                $(form).find('[name="' + error + '"]').removeClass("has-error").removeClass("has-success").parents('.custom-control.custom-radio.custom-control-inline').parent().removeClass("has-error").addClass("has-success");
                            } else if ($('[name="' + error + '"]')[0].tagName.toLocaleLowerCase() === "select") {
                                $(form).find('[name="' + error + '"]').removeClass("has-error").addClass("has-success").parent().find(".select2-selection").removeClass("has-error").addClass("has-success");
                            } else {

                                $(form).find('[name="' + error + '"]').removeClass("has-error").addClass("has-success");

                            }
                        }

                    }
                },
                scroll: (e, name = null) => {
                    var a = e.attr("href");
                    if ($(a).length) {
                        var t = e.data("scroll-to-offset") ? e.data("scroll-to-offset") : 0,
                            n = {
                                scrollTop: $(a).offset().top - t - 50
                            };

                        if (settings.containerScroll) {
                            settings.containerScroll.animate({
                                scrollTop: settings.containerScroll.scrollTop() + n.scrollTop - settings.containerScroll.offset().top
                            }, {
                                duration: "slow",
                                easing: "swing"
                            });
                        } else {
                            $("html,body").stop(!0, !0).animate(n, {
                                duration: 1000,
                                easing: "swing"
                            });
                        }
                        if (name && $(this).find('[name="' + name + '"]').length) {
                            $(this).find('[name="' + name + '"]').focus();
                        }
                    }

                }
            },
            onSubmit = e => {
                e.preventDefault();
                var fields = $(this).serializeArray().reduce(function (obj, item) {
                    obj[item.name] = item.value;
                    return obj;
                }, {});

                if (validation.validate(fields, this, newRules)) {
                    settings.data = new FormData(e.target);
                    ajax();
                }
            };

        $(this).on("submit", function (e) {
            options.hasOwnProperty("onSubmit") && "function" == typeof options.onSubmit ? settings.onSubmit(e, validation,load) : onSubmit(e);
        });

        $(this).find("input[name] , textarea[name]").on("input", function () {
            var key = $(this).attr("name"),
                value = $(this).val(),
                fields = {
                    [key]: value
                };
            if (validation.validate(fields, $(this).parents("form"), newRules)) {
                $(this).removeClass("has-error").addClass("has-success")
                    .parents().find(".invalid-feedback");
            };

        }).removeAttr("required");

        // $(this).find("select[name]").each(function () {
        //     $(this).val($(this).attr("data-select-value") ? $(this).attr("data-select-value").split(",") : 0).trigger("change").removeAttr("required").select2().on("select2:select", function (e) {
        //         $(this).removeClass("has-error").parent().find(".invalid-feedback").remove();
        //         $(this).parent().find(".select2-selection").removeClass("has-error").addClass("has-success");
        //     });
        // });


    };
})(jQuery);
